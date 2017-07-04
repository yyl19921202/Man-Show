<?php
namespace frontend\controllers;
use backend\models\Article;
use backend\models\Brand;
use backend\models\GoodsIntro;
use backend\models\Menu;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\GoodsCategory;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Shopdetails;
use Symfony\Component\BrowserKit\Cookie;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ApiController extends Controller{
    public $enableCsrfValidation =false;//写接口一定要写这句

    public function init()
    {
        \Yii::$app->response->format=Response::FORMAT_JSON;
        parent::init();
    }
    //api会员注册接口
    public function actionMember(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $member=new Member();
            $member->username=$request->post('username');
            $member->password_hash=\Yii::$app->security->generatePasswordHash($request->post('userpassword'));
            $member->email=$request->post('email');
            $member->tel=$request->post('tel');
            if($member->validate()){
//                var_dump($member);exit;
                $member->save();
                return ['status'=>'1','msg'=>'','data'=>$member->toArray()];
            }
            //验证失败时提示
            return ['status'=>'-1','msg'=>$member->getErrors()];
        }
        return ['status'=>'-1','msg'=>'请使用POST提交'];
    }
    //api会员登陆接口
    public function actionLogin(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $user=Member::findOne(['username'=>$request->post('username')]);
            if($user && \Yii::$app->security->validatePassword($request->post('userpassword'),$user->password_hash)){
                \Yii::$app->user->login($user);
                return ['status'=>'1','mag'=>'登陆成功'];
            }
            return ['status'=>'-1','msg'=>'账号或密码错误'];
        }
        return ['status'=>'-1','msg'=>'请使用post提交'];
    }
    //添加地址
    public function actionAddress(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $address=new Shopdetails();
            $address->receiver=$request->post('receiver');
            $address->site=$request->post('site');
            $address->site2=$request->post('site2');
            $address->site3=$request->post('site3');
            $address->address=$request->post('address');
            $address->tel=$request->post('tel');
            $address->user_id=\Yii::$app->user->getId();
            if($address->validate()){
                $address->save();
                return ['status'=>'1','msg'=>'地址添加成功'];
            }
            return ['status'=>'-1','msg'=>'地址添加失败'];
        }
        return ['status'=>'-1','msg'=>'请使用post方式提交'];
    }
    //api接口修改地址
    public function actionEditAddress(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $id=$request->post('id');
//            var_dump($id);exit;
            $address=Shopdetails::findOne(['id'=>$id]);
            if($address!=null){
                $address->receiver=$request->post('receiver');
                $address->site=$request->post('site');
                $address->site2=$request->post('site2');
                $address->site3=$request->post('site3');
                $address->address=$request->post('address');
                $address->tel=$request->post('tel');
                if($address->validate()){
                    $address->save();
                    return ['status'=>'1','msg'=>'地址修改成功'];
                }
                return ['status'=>'-1','msg'=>'修改失败'];
            }
            return ['status'=>'-1','msg'=>'请使用post方式提交'];
        }
    }
    //删除地址
    public function actionDelAddress(){
        $request=\Yii::$app->request;
        if($request->isGet){
            $id=$request->get('id');
//            var_dump($id);exit;
            $address=Shopdetails::findOne(['id'=>$id]);
            if($address!=null){
                $address->delete();
                return ['status'=>'1','msg'=>'删除成功'];
            }
            return ['status'=>'-1','msg'=>'删除失败'];
        }
        return ['status'=>'-1','msg'=>'请使用get方式提交'];
    }
    //地址列表
    public function actionIndex(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $user_id=$request->post('user_id');
            $address=Shopdetails::findAll(['user_id'=>$user_id]);
            if($address!=null){
                return ['status'=>'1','data'=>$address,'msg'=>''];
            }
            return ['status'=>'-1','msg'=>'列表查询失败'];
        }
        return ['status'=>'-1','msg'=>'请使用post方式提交'];
    }
    //api商品分类
    public function actionGoodsCategory(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $parent_id=$request->post('parent_id');
            $goods=GoodsCategory::findAll(['parent_id'=>$parent_id]);//查询出所有的一级分类
            if($goods!=null){
                return ['status'=>'1','data'=>$goods,'msg'=>''];
            }
            return ['status'=>'-1','msg'=>'查询失败'];
        }
        return ['status'=>'-1','msg'=>'请使用post方式提交'];
    }
    //-获取某分类的所有子分类
    public function actionChildCategory(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $pid=$request->post('id');
            $child=GoodsCategory::findAll(['parent_id'=>$pid]);
            if($child!=null){
                return ['status'=>'1','data'=>$child,'msg'=>''];
            }
            return ['status'=>'-1','msg'=>'对不起，没有找到该分类'];
        }
        return ['status'=>'-1','msg'=>'请使用post方式提交'];
    }
    //-获取某分类下面的所有商品（不考虑分页）
    public function actionGoodsAll(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $goods_category_id=$request->post('goods_category_id');
            $goodscategory=Goods::findAll(['goods_category_id'=>$goods_category_id]);
            if($goodscategory!=null){
                return ['status'=>'1','data'=>$goodscategory,'msg'=>''];
            }
            return ['status'=>'-1','msg'=>'对不起，没有找到商品'];
        }
        return ['status'=>'-1','msg'=>'请使用post方式提交'];
    }
    //获取某品牌下面的所有商品
    public function actionBrandAll(){
        if($brand_id = \Yii::$app->request->post('brand_id')){
            $goods = Goods::find()->where(['brand_id'=>$brand_id])->asArray()->all();
//            var_dump($goods);exit;
            return ['status'=>1,'msg'=>'','data'=>$goods];
        }
        return ['status'=>'-1','msg'=>'参数不正确'];
    }
    //获取某分类下面的所有文章
    public function actionArticle(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $acd=$request->post('article_category_id');
            $goods=Article::findAll(['article_category_id'=>$acd]);
            if($goods!=null){
                return ['status'=>'1','msg'=>'','data'=>$goods];
            }
            return ['status'=>'-1','msg'=>'没有该文章'];
        }
        return ['status'=>'-1','msg'=>'请使用POST方式提交'];
    }
    //获取某文章所属分类
    public function actionArticleCategory(){
        $request=\Yii::$app->request;
        if($request->isPost){
        $id=$request->post('article_category_id');
        $article=Article::findAll(['id'=>$id]);
        if($article!=null){
            return ['status'=>'1','msg'=>'','data'=>$article];
        }
        return ['status'=>'-1','msg'=>'没有找到该分类'];

        }
        return['status'=>'-1','msg'=>'请使用post方式提交'];
    }
    //添加商品到购物车 api接口
    public function actionAddCart(){
    $request=\Yii::$app->request;
    if($request->isPost){
        $amount=\Yii::$app->request->post('amount');
        $goods_id=\Yii::$app->request->post('goods_id');
        $goods=Goods::findOne(['id'=>$goods_id,]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
            }
        if(\Yii::$app->user->isGuest){//未登录状态
            //设置cookie在从cookie获取数据
            $cookies=\Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');//获取cookie数据
            if($cookie==null){
                $cart=[];
            }else{
                $cart=unserialize($cookie->value);
            }
            //将商品数量id保存到cookie中
            $cookies=\Yii::$app->response->cookies;//设置cookie
            if(key_exists($goods_id,$cart)){//检查购物车中有没有商品  key_exists有没有包含该商品id
                $cart[$goods_id]+=$amount;//有数量就累加
                }else{
                $cart[$cart]=$amount;
                }
                //实例化数据
                $cookie=new \yii\web\Cookie([
                    'name'=>'cart','value'=>serialize($cart)
                ]);
            $cookies->add($cookie);//保存cookie
            }else{
            //登陆状态
            $cart=new Cart();//实例化对象
            if($cart->validate()){
                $cart->goods_id=$goods_id;
                $cart->amount=$amount;
                $cart->member_id=\Yii::$app->user->getId();
                $cart->save();
                }
            }
        }
        return ['status'=>'1','msg'=>'','data'=>true];
    }
    //修改购物车某商品数量
    public function actionEditCart(){
    $request=\Yii::$app->request;
    if($request->isPost){
        $goods_id=\Yii::$app->request->post('goods_id');
        $amount=\Yii::$app->request->post('amount');
        $goods=Goods::findOne(['id'=>$goods_id]);//根据传过来的商品id找到该商品
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        if(\Yii::$app->user->isGuest){//未登录时修改
            $cookies=\Yii::$app->request->cookies;//设置cookie
            $cookie=$cookies->get('cart');//获取cookie里面的数据
            if($cookie==null){
                $cart=[];
            }else{
                $cart=unserialize($cookie->value);
            }
            //将商品id、数量存cookie
            //设置cookie
            $cookies=\Yii::$app->response->cookies;
            if($amount){
                $cart[$goods_id]=$amount;
            }else{
                if(key_exists($goods['id'],$cart))unset($cart[$goods_id]);
            }
            $cookie=new \yii\web\Cookie([
                'name'=>'cart','value'=>serialize($cart)
            ]);
            $cookies->add($cookie);
        }else{
            $goods=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id]);
            if($amount==0){
                $goods->delete();
                return ['status'=>'1','msg'=>'删除成功','data'=>true];
            }else{
                $goods->amount=$amount;
                $goods->save();
            }
        }
        return ['status'=>'1','msg'=>'修改','data'=>true];
    }
    return ['status'=>'-1','msg'=>'修改失败'];
}
    //获取购物车所有商品
    public function actionGetCart(){
        $request=\Yii::$app->request;
        if($request->isGet){
            if(\Yii::$app->user->isGuest){//未登录
                $cookies=\Yii::$app->request->cookies;//设置cookie
                $cookie=$cookies->get('cart');
                if($cookie==null){
                    $cart=[];
                }else{
                    $cart=unserialize($cookie->value);
                }
                return ['status'=>'1','msg'=>'','data'=>$cart];
            }else{
                $member_id=\Yii::$app->user->id;//获取当前用户id
                $goods=Cart::findAll(['member_id'=>$member_id]);
                return ['status'=>'1','msg'=>'','data'=>$goods];
            }
        }
        return ['status'=>'-1','msg'=>'请使用get方式提交'];
    }
    //清空购物车
    public function actionEmptyCart(){
        $request=\Yii::$app->request;
        if($request->isGet){
            if(\Yii::$app->user->isGuest){//未登录的情况下 清空cookie里面的所有数据
                $cookies=\Yii::$app->request->cookies;//设置cookie
                $cookie=$cookies->get('cart');//获取购物车里面的数据
                if($cookie!=null){
                    $cart=[];
                    return ['status'=>'1','msg'=>'','data'=>$cart];
                }
            }else{
                $member_id=\Yii::$app->user->id;//获取当前登录用户
                $goods=Cart::findAll(['member_id'=>$member_id]);
                if($goods!=null){
                    foreach ($goods as $good){
                        $good->delete();
                    }
                }
                return ['status'=>'1','msg'=>'删除成功'];
            }
            return ['status'=>'-1','msg'=>'请使用post方式提交'];
        }
    }
    //获取支付方式
    public function actionPay(){
        $request=\Yii::$app->request;
        if($request->isGet){
            $pay_name=Order::$_pay;
            return $pay_name;
        }
    }
    //获取送货方式
    public function actionMethod(){
        $request=\Yii::$app->request;
        if($request->isGet){
            $method=Order::$_method;
            return $method;
        }
    }
    //提交订单
    public function actionOrder()
    {
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model = new Order();
            $id = $request->post('id');
            $address = Shopdetails::findOne(['id' => $id, 'user_id' => \Yii::$app->user->id]);
            if ($address == null) {
                throw new NotFoundHttpException('该地址不存在');
            }
            $model->name = $address->receiver;
            $model->member_id = $address->user_id;
            $model->province = $address->site;
            $model->city = $address->site2;
            $model->area = $address->site3;
            $model->address = $address->address;
            $model->tel = $address->tel;
            $model->member_id = $address->user_id;
            //送货方式
            $delivery_id = $request->post('delivery_id');
            $model->delivery_id = $delivery_id;
            $model->delivery_name = Order::$_method[$delivery_id]['delivery_name'];
            //支付方式
            $payment_id = $request->post('payment_id');
            $model->payment_id = $payment_id;
            $model->payment_name = Order::$_pay[$payment_id]['payment_name'];
            $model->create_time = time();
            $model->save();
            //保存订单详情
            $carts = Cart::findAll(['member_id' => \Yii::$app->user->id]);
            foreach ($carts as $cart) {
                $goods = Goods::findOne(['id' => $cart->goods_id]);
                if ($goods == null) {
                    throw new NotFoundHttpException('商品已售完');
                }
                $order_goods = new OrderGoods();
                $order_goods->goods_id = $goods->id;
                $order_goods->goods_name = $goods->name;
                $order_goods->logo = $goods->logo;
                $order_goods->price = $goods->shop_price;
                $order_goods->amount = $cart->amount;
                $order_goods->total = $order_goods->price * $order_goods->amount;
                $order_goods->save();
            }
            return ['status' => '1', 'msg' => ''];
        }
    }
    //获取当前用户订单列表
    public function actionGetOrder(){
        $request=\Yii::$app->request;
        if($request->isGet){
            $orders=Order::findAll(['member_id'=>\Yii::$app->user->id]);
            foreach ($orders as $order){
                $order_goods=OrderGoods::findAll(['order_id'=>$order->id]);
            }
            return['status'=>'1','msg'=>'','data'=>[$orders,$order_goods]];
        }
        return['status'=>'-1','msg'=>'请使用get方式传值'];
    }
    //取消订单
    public function actionOffOrder(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $id=$request->post('order_id');
            $order=Order::findOne(['id'=>$id,'member_id'=>\Yii::$app->user->id]);
//            var_dump($order);exit;
            if($order==null){
                throw new NotFoundHttpException('该订单不存在');
            }
            $order->status='0';
            $order->save();
            return['status'=>'1','msg'=>'清单取消成功'];
        }
        return['status'=>'-1','msg'=>'请使用post方式传值'];
    }


















}