<?php

namespace frontend\controllers;

use backend\models\GoodsCategory;
use backend\models\Goods;
use backend\models\Img;
use backend\models\OrdersGoods;
use frontend\assets\AddressAsset;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Locations;
use frontend\models\LogForm;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\shopdetails;
use Symfony\Component\BrowserKit\Cookie;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
use yii\web\NotFoundHttpException;

class UserController extends \yii\web\Controller
{
    public $layout = 'login';

    //用户注册
    public function actionRegister()
    {
        $model = new Member();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //密码加密  hash
            $model->created_at = time();
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->userpassword);
            $model->save(false);
            \Yii::$app->session->setFlash('success', '注册成功');
            $this->redirect(['user/login']);
        }
        return $this->render('register', ['model' => $model]);
    }
    //短信验证
    public function actionSendSms(){
        // 配置信息
        $config = [
            'app_key' => '	24479524',
            'app_secret' => 'aba7f7cf7e6b5361d937e25b0500109f',
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];
        // 使用方法一
        $tel = \Yii::$app->request->post('tel');
        if(!preg_match('/^1[34578]\d{9}$/',$tel)){
            echo '电话号码不正确';
            exit;
        }
        $client = new Client(new App($config));
        $req = new AlibabaAliqinFcSmsNumSend;
        $name = rand(100000, 999999);
        $req->setRecNum($tel)
            ->setSmsParam([
                'name' => $name
            ])
            ->setSmsFreeSignName('叶延林的网站')
            ->setSmsTemplateCode('SMS_71660180');
        $resp = $client->execute($req);
        if($resp){
            \Yii::$app->cache->set('tel_'.$tel,$name,5*60);//保存验证码和电话号码并且匹配，设置一个5分钟的有效时间
            echo '发送成功';
        }else{
            echo '发送失败';
        }
    }



    //用户登录
    public function actionLogin()
    {
        $model = new LogForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            \Yii::$app->session->setFlash('success', '登陆成功');
            return $this->redirect(['user/index']);
        }
        return $this->render('login', ['model' => $model]);
    }

    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }


    //商品收货地址:
    public function actionAddress()
    {
        //新增收货地址
        $this->layout = 'address';
        $model = new Shopdetails();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->user_id = \Yii::$app->user->getId();
            $model->save();
        }
        $models = Shopdetails::find()->all();
        return $this->render('address', ['model' => $model, 'models' => $models]);
    }

    //删除收货地址
    public function actionDel($id)
    {
        $model = Shopdetails::findOne(['id' => $id]);
        $model->delete();
        return $this->redirect(['user/address']);
    }

    //修改收货地址
    public function actionEdit($id)
    {
        $model = Shopdetails::findOne(['id' => $id]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->save();
        }
        $models = Shopdetails::find()->all();
        return $this->render('address', ['model' => $model, 'models' => $models]);
    }


    //商品分类  分词搜索
    public function actionIndex()
    {
        $this->layout = 'index';

        //获取商品
        $shops = GoodsCategory::findAll(['parent_id' => 0]);
        return $this->render('index', ['shops' => $shops]);
    }


//短信验证码

    public function actionSms()
    {
        // 配置信息
        $config = [
            'app_key' => '	24479524',
            'app_secret' => 'aba7f7cf7e6b5361d937e25b0500109f',
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];
        // 使用方法一
        $client = new Client(new App($config));
        $req = new AlibabaAliqinFcSmsNumSend;
        $name = rand(100000, 999999);
        $req->setRecNum('15208412131')
            ->setSmsParam([
                'name' => $name
            ])
            ->setSmsFreeSignName('叶延林的网站')
            ->setSmsTemplateCode('SMS_71660180');
        $resp = $client->execute($req);
        var_dump($resp);
        var_dump($name);
    }

    //商品列表页
    public function actionList()
    {
        $this->layout = "list";
        if($contents=\Yii::$app->request->get('content')){
            $cl = new SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);
            $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
            // $cl->SetMatchMode ( SPH_MATCH_ANY);
            $cl->SetMatchMode ( SPH_MATCH_ALL);
            $cl->SetLimits(0, 1000);
            $res = $cl->Query($contents, 'goods');//shopstore_search
            if($res==false){
                throw new NotFoundHttpException('您搜索的物品不存在');
            }else{
                $ids = ArrayHelper::map($res['matches'],'id','id');
        var_dump($ids);exit;
            }
        }
        $goods = Goods::find()->all();
        return $this->render('list', ['goods' => $goods]);
    }

    //商品详情页
    public function actionGoods($id)
    {
        $this->layout = 'goods';
        $imgs = Img::find()->where(['goods_id' => $id])->all();
        $goods = Goods::findOne(['id' => $id]);
        return $this->render('goods', ['goods' => $goods, 'imgs' => $imgs]);
    }


    //添加购物车
    public function actionCart()
    {
        //接收用户传过来的商品信息：商品数量、商品信息
        $amount = \Yii::$app->request->post('amount');//接受传过来的数量
        $goods_id = \Yii::$app->request->post('goods_id');//接受商品ID
        $goods = Goods::findOne(['id' => $goods_id]);//根据id获取数据s
        if ($goods == null) {//判断商品是否为空
            throw new NotFoundHttpException('商品不存在');//抛出提示
        }
        if (\Yii::$app->user->isGuest) {//判断用户的登陆状态   未登录状态
            $cookies = \Yii::$app->request->cookies;//设置cookie
            $cookie = $cookies->get('cart');//获取cookie里面有没有cart的这个数据
            if ($cookie == null) {//判断cookies里面的数据是否为空
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);//反序列话
            }
            //将商品数量、id保存到cookie
            $cookies = \Yii::$app->response->cookies;
            if (key_exists($goods_id, $cart)) {//检查购物车中是否有商品
                $cart[$goods_id] += $amount;//有就累加
            } else {
                $cart[$goods_id] = $amount;
            }
            //实例化cookie.
            $cookie = new \yii\web\Cookie([
                'name' => 'cart', 'value' => serialize($cart) //实例化 数据
            ]);
            $cookies->add($cookie); //保存cookie
//            var_dump($cookies);exit;
        } else {
            //已登陆的状态
            $model = new Cart();
            if ($model->validate()) {//判断传过来的数据是否符合要求
                $model->goods_id = $goods_id;
                $model->amount = $amount;
                $model->member_id = \Yii::$app->user->getId();
                $model->save();
            }
        }
        return $this->redirect(['user/flow1']);
    }


    //购物车
    public function actionFlow1()
    {
        $this->layout = 'flow1';
        $cates = [];
        $sum = 0;
        if (\Yii::$app->user->isGuest) {
            //未登陆
            $cookies = \Yii::$app->request->cookies;//设置cookie
            $cookie = $cookies->get('cart');//取出cookie中的值
//            var_dump($cookie);exit;
            if ($cookie == null) {
                //如果cookie中没有购物车数据
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);
            }
            foreach ($cart as $good_id => $amount) {
                $goods = Goods::findOne(['id' => $good_id])->attributes;
                $goods['amount'] = $amount;
                $sum += $goods['amount'] * ($goods['shop_price']);
                $cates[] = $goods;
            }
            //已登陆
        } else {

            $sum = 0;
            $models = Cart::findAll(['member_id' => \Yii::$app->user->getId()]);
            foreach ($models as $model) {
//              var_dump(\Yii::$app->user->getId());exit;
                $goods = Goods::findOne(['id' => $model->goods_id])->attributes;
                $goods['amount'] = $model->amount;
                $sum += $goods['amount'] * ($goods['shop_price']);
                $cates[] = $goods;
            }
        }
        return $this->render('flow1', ['cates' => $cates, 'sum' => $sum]);
    }

//修改购物车数据
    public function actionUpdateCart()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');

//        echo json_encode(123);exit;
        $goods=Goods::findOne(['id'=>$goods_id]);
        if ($goods == null) {
            throw new NotFoundHttpException('商品不存在');
        }
        if (\Yii::$app->user->isGuest) {
            //设置cookie,获取cookie里面的数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            if ($cookie == null) {
                $cart = [];
            } else {
                $cart = unserialize($cookie->value);
            }
            //将商品id、数量存到cookie中
            $cookies = \Yii::$app->response->cookies;
            if ($amount) {
                $cart[$goods_id] = $amount;
            } else {
                if (key_exists($goods['id'], $cart)) unset($cart[$goods_id]);
            }
            $cookie = new \yii\web\Cookie([
                'name' => 'cart', 'value' => serialize($cart)
            ]);
            $cookies->add($cookie);
        } else {
            $goods=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id]);
//            var_dump($goods);exit;
            if($amount){
                $goods->amount=$amount;
                $goods->save();
            }else{
               $goods->delete();
            }
        }
        return $this->redirect(['user/flow1']);
    }

    //提交到订单列表
    public function actionFlow2()
    {
        $this->layout = 'flow2';
        $sum = 0;
        $addres = Shopdetails::find()->all(); //查询地址
        $shops = Cart::findAll(['member_id' => \Yii::$app->user->getId()]);
        $cates = [];
        foreach ($shops as $shop) {
            $goods = Goods::findOne(['id' => $shop->goods_id])->attributes;
            $goods['amount'] = $shop->amount;
            $sum += $goods['amount'] * ($goods['shop_price']);
            $cates[] = $goods;
//            var_dump($cates);exit;
        }
        return $this->render('flow2', ['cates' => $cates, 'addres' => $addres, 'sum' => $sum]);
    }

    //保存订单信息
    public function actionFlow3()
    {
        $this->layout = 'flow3';
        $model = new Order();
        //根据地址的id 和用户id 找到该条数据
        $data = \Yii::$app->request->post();//接受用户传过来的参数
        //根据地址id和用户id找到该条数据
        $address = Shopdetails::findOne(['id' => $data['address_id'], 'user_id' => \Yii::$app->user->id]);//找到地址id和用户id
        if ($address == null) { //判断参数
            throw new NotFoundHttpException('地址不存在');
        }
        $model->name = $address->receiver;
        $model->member_id = $address->user_id;
        $model->province = $address->site;
        $model->city = $address->site2;
        $model->area = $address->site3;
        $model->address = $address->address;
        $model->tel = $address->tel;
//        var_dump($model);exit;
        //送货方式  赋值
        $model->delivery_name = Order::$_method[$data['delivery']]['delivery_name'];
        $model->delivery_id = $data['delivery'];

        //支付方式 赋值
        $model->payment_name = Order::$_pay[$data['pay']]['payment_name'];
        $model->payment_id = $data['pay'];
        $model->create_time = time();
        $model->save();
//        var_dump($model);exit;
        //保存订单商品
            $carts = Cart::findAll(['member_id' => \Yii::$app->user->id]);//根据用户id去购物车找到该用户所有数据
//            var_dump($carts);exit;
            foreach ($carts as $cart) {
                $goods = Goods::findOne(['id' => $cart->goods_id]);
//                var_dump($goods);exit;
                if ($goods==null) {
                    throw new NotFoundHttpException('商品已售完');
                }
                $order_goods = new OrderGoods();
                $order_goods->order_id=$model->id;
//                var_dump($model->id);exit;
                $order_goods->goods_id=$goods->id;
                $order_goods->goods_name=$goods->name;
                $order_goods->logo=$goods->logo;
                $order_goods->price=$goods->shop_price;
                $order_goods->amount=$cart->amount;
                $order_goods->total=$order_goods->price*$order_goods->amount;
                $order_goods->save();
            }
            return $this->render('flow3');
}


    //分词搜索测试
//    public function actionTest(){
//        $cl = new SphinxClient();
//        $cl->SetServer ( '127.0.0.1', 9312);
////$cl->SetServer ( '10.6.0.6', 9312);
////$cl->SetServer ( '10.6.0.22', 9312);
////$cl->SetServer ( '10.8.8.2', 9312);
//        $cl->SetConnectTimeout ( 10 );
//        $cl->SetArrayResult ( true );
//// $cl->SetMatchMode ( SPH_MATCH_ANY);
//        $cl->SetMatchMode ( SPH_MATCH_ALL);
//        $cl->SetLimits(0, 1000);
//        $info = '宠物猫';
//        $res = $cl->Query($info, 'goods');//shopstore_search
////print_r($cl);
//        var_dump($res);
//    }



    //验证码
    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>3,//验证码最小长度
                'maxLength'=>3,//最大长度
            ],
        ];
    }
}
