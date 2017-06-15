<?php
namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\SouSuo;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsController extends Controller{
    //添加
    public function actionAdd(){
        $model=new Goods();//实例化一个对象
        $models=new GoodsIntro();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $models->load($request->post());
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');//实例化一个图片上传文件
            if($model->validate() && $models->validate()){
                if($model->imgFile){//判断图片是否符合要求
                    $fileName='/images/goods'.uniqid().'.'.$model->imgFile->extension;//创建图片路径，并随机命名
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);//将图片移动到指定位置
                    $model->logo=$fileName;//图片赋值
                }
                //自动生成货号 时间加编号的格式
                $day=date('Y-m-d');//当前时间
                $goodsCount=GoodsDayCount::findOne(['day'=>$day]);//根据当天的时间找到记录
                if($goodsCount==null){//判断找到的那条记录为空吗？
                    $goodsCount=new GoodsDayCount();//实例化一个模型
                    $goodsCount->day=$day;//将数据库里面的日期设置为当天日期
                    $goodsCount->count=0;//将内容设置为0.
                }else{
                    $goodsCount->count+=1;//否则在count的基础上加1
                }
                $goodsCount->save();//最后保存起
                $model->sn=date('Ymd').sprintf("%04d",$goodsCount->count);//将内容以时间加货号的格式拼凑起来，并且自动加1
                $model->save();
                $model->create_time=time();
                $model->save();
                $models->goods_id=$model->id;
                $models->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }
        }
        //获取前面2个表的内容
        $brands=ArrayHelper::map( Brand::find()->asArray()->all(),'id','name');
        $goodscategory=ArrayHelper::map(GoodsCategory::find()->orderBy('tree,lft')->asArray()->all(),'id','name');
        return $this->render('add',['model'=>$model,'brands'=>$brands,'goodscategory'=>$goodscategory,'models'=>$models]);
    }


    //显示列表
    public function actionIndex(){
        //搜索
        $query=Goods::find();
        if($data=\Yii::$app->request->get('data')){
            $query->andWhere(['like','name',$data]);
        }
        $goods=$query->all();
        return $this->render('index',['goods'=>$goods]);
    }


    //显示文章内容
    public function actionLook($id){
        $ready=GoodsIntro::findOne(['goods_id'=>$id]);
        $looks=Goods::findOne(['id'=>$id]);

        return $this->render('look',['ready'=>$ready,'looks'=>$looks]);
    }

    //删除
    public function actionDel($id){
        $model=Goods::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['goods/index']);
    }


    public function actionEdit($id){
        $model=Goods::findOne(['id'=>$id]);//实例化一个对象
        $models=GoodsIntro::findOne(['goods_id'=>$id]);
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $models->load($request->post());
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');//实例化一个图片上传文件
            if($model->validate() && $models->validate()){
                if($model->imgFile){//判断图片是否符合要求
                    $fileName='/images/goods'.uniqid().'.'.$model->imgFile->extension;//创建图片路径，并随机命名
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);//将图片移动到指定位置
                    $model->logo=$fileName;//图片赋值
                }
                $model->create_time=time();
                $model->save();
                $models->goods_id=$model->id;
                $models->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods/index']);
            }
        }
        //获取前面2个表的内容
        $brands=ArrayHelper::map( Brand::find()->asArray()->all(),'id','name');
        $goodscategory=ArrayHelper::map(GoodsCategory::find()->orderBy('tree,lft')->asArray()->all(),'id','name');
        return $this->render('add',['model'=>$model,'brands'=>$brands,'goodscategory'=>$goodscategory,'models'=>$models]);
    }

    //搜索
    public function actionSou(){
        $model=new SouSuo();
        $query=Goods::find();

    }
}