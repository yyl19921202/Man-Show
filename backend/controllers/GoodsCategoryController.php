<?php
namespace backend\controllers;
use backend\models\GoodsCategory;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class GoodsCategoryController extends Controller{
    //添加
    public function actionAdd(){
        $model=new GoodsCategory();
            $request=new Request();
            if($request->isPost){
                $model->load($request->post());
                if($model->validate()){
                    //判断是否添加一级分类
                    if($model->parent_id){
                        //添加非一级分类
                        $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);//获取上一级分类
                        $model->prependTo($parent);//添加到上一级分类下面
                    }else{
                        //添加一级分类
                        $model->makeRoot();
                    }
                    //提示
                    \Yii::$app->session->setFlash('success','添加成功');
                    return $this->redirect(['goods-category/index']);
                }
            }

        //获取所有分类数据
        $categories=GoodsCategory::find()->asArray()->all();
//        $categories=array_merge(['name'=>'最上级','id'=>0,'parent_id'=>0],$categories);
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }

        //显示列表
    public function actionIndex(){
           $goods=GoodsCategory::find()->all();

           return $this->render('index',['goods'=>$goods]);
    }
    //修改
    public function actionEdit($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断是否添加一级分类
                if($model->parent_id){
                    //添加非一级分类
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);//获取上一级分类
                    $model->prependTo($parent);//添加到上一级分类下面
                }else{
                    //添加一级分类
                    $model->makeRoot();
                }
                //提示
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods-category/index']);
            }
        }

        //获取所有分类数据
        $categories=GoodsCategory::find()->asArray()->all();
//        $categories=array_merge(['name'=>'最上级','id'=>0,'parent_id'=>0],$categories);
        return $this->render('add',['model'=>$model,'categories'=>$categories]);

    }





//    //测试
//    public function actionTree(){
//
//        $goodcates=GoodsCategory::find()->asArray()->all();
//
//        return $this->renderPartial('tree',['goodcates'=>$goodcates]);
//    }

   // 测试
//    public function actionTest(){
//        //创建一个一级分类
//        $djd=new GoodsCategory();
//        $djd->name='大家电';
//        $djd->parent_id=0;
//        $djd->makeRoot();//将当前分类设置为一级分类
//
//    }
}