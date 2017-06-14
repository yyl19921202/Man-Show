<?php
namespace backend\controllers;
use backend\models\User;
use yii\web\Controller;
use yii\web\Request;

class UserController extends Controller{
    //验证码
    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,//验证码最小长度
                'maxLength'=>4,//最大长度
            ],
        ];
    }
    //添加表单
    public function actionAdd(){
        $model=new User();//实例化一个对象
        $request=new Request();
        if($request->isPost){//判断传输方式
            $model->load($request->post());//加载内容
            if($model->validate()){
                $model->save();
            }
        }
        return $this->render('add',['model'=>$model]);
    }
}