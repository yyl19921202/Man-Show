<?php
namespace backend\controllers;
use backend\models\ArticleCategory;
use yii\web\Controller;
use yii\web\Request;

class ArticleCategoryController extends Controller{
    //添加
    public function actionAdd(){
        $model=new ArticleCategory();//实例化一个对象
        $request=new Request();
        if($request->isPost){//判断用户是否是post方式传输
            $model->load($request->post());//加载数据
            if($model->validate()){//判断用户传过来的数据是否合法
                $model->save();//保存数据
                \Yii::$app->session->setFlash('success', '品牌添加成功');//提示用户添加成功
               return $this->redirect(['article-category/index']);//返回首页
            }else{
                var_dump($model->getErrors());exit;//打印错误信息
            }
        }
        return $this->render('add',['model'=>$model]);//返回
    }

    //显示商品分类列表
    public function actionIndex(){
        $acs=ArticleCategory::find()->all();//找到内容

        return $this->render('index',['acs'=>$acs]);//返回到现实页面
    }

    //删除
    public function actionDel($id)
    {
        $model = ArticleCategory::findOne(['id' => $id]);//根据id找到商品
        $model->status=-1;
        $model->save();//保存
        \Yii::$app->session->setFlash('success', '删除成功');//提示用户删除成功
        return $this->redirect(['article-category/index']);//返回首页
    }
    //修改
    public function actionEdit($id){
        $model=ArticleCategory::findOne(['id'=>$id]);//根据id找到行
        $request=new Request();
        if($request->isPost){//判断用户是否是post方式传输
            $model->load($request->post());//加载数据
            if($model->validate()){//判断用户传过来的数据是否合法
                $model->save();//保存数据
                \Yii::$app->session->setFlash('success','修改成功');//修改成功提示
                return $this->redirect(['article-category/index']);//返回首页
            }
        }
        return $this->render('add',['model'=>$model]);//返回
    }
}