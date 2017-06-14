<?php
namespace backend\models;
use yii\db\ActiveRecord;

class User extends ActiveRecord{
    public $code;
    public function rules()
    {
        return [
            [['username','password_hash','email'],'required'],
            ['code','captcha','captchaAction'=>'user/captcha'],
            ['username','unique'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'email'=>'邮箱',
            'code'=>'验证码',
        ];
    }
}