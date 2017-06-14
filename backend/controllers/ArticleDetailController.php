<?php
namespace backend\controllers;
use backend\models\ArticleDetail;
use yii\web\Controller;

class ArticleDetailController extends Controller{
    public function actionAdd(){
        $model=new ArticleDetail();

        return $this->render('add',['model'=>$model]);
    }
}