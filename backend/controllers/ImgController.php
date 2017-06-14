<?php
namespace backend\controllers;
use backend\models\Goods;
use backend\models\Img;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class ImgController extends Controller{
    public function actionAdd(){
        $model=new Img();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');//实例化一个图片上传文件
            if($model->validate()){
                if($model->imgFile){//判断图传值是否符合要求
                    $fileName='/images/img'.uniqid().'.'.$model->imgFile->extension;//创建图片路径，并随机命名
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);//将图片移动到指定位置
                    $model->img=$fileName;//图片赋值
                    $model->save();
                    return $this->redirect(['img/index','id'=>$model->goods_id]);
                }
            }
        }
        //获取goods商品名称
        $goods=ArrayHelper::map(Goods::find()->asArray()->all(),'id','name');
        return $this->render('add',['model'=>$model,'goods'=>$goods]);
    }
    //显示相册
    public function actionIndex($id){
        //获取图片信息
        $imgs=Img::find()->where(['goods_id'=>$id])->all();
        return $this->render('index',['imgs'=>$imgs]);

    }
}