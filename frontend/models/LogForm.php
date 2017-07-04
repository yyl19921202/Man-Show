<?php
namespace frontend\models;
use yii\base\Model;
use Yii;
class LogForm extends Model{
    public $code;//验证码
    public $password_hash;
    public $username;
    public $remerberMe;

    public function rules()
    {
        return [
            [['password_hash','username'],'required'],
            ['remerberMe','safe'],
            ['username','proving'],
//            ['code','captcha','captchaAction'=>'user/captcha'],
        ];
    }
    public function attributeLabels()
    {
        return [
//            'code'=>'验证码',
            'password_hash'=>'密码',
            'username'=>'用户名',
            'remerberMe'=>'记住密码',
        ];
    }
    //登陆自定义验证方法
    public function Proving(){
        //根据用户名找到这条记录
        $users=Member::findOne(['username'=>$this->username]);
        if($users){//判断
            if(Yii::$app->security->validatePassword($this->password_hash,$users->password_hash)){//判断明文和密文是否相等
                //自动登录
                $data=$this->remerberMe?1*24*3600:0;//判断选中，是的话 就给个有效时间
                $users->last_login_time=time();//最后登录时间
                $users->last_login_ip=Yii::$app->request->getUserIP();//登陆ip
                $users->save(false);
                Yii::$app->user->login($users,$data);//登陆
            }else{
                $this->addError('password_hash','用户名或密码不正确');
            }
        }else{
            $this->addError('username','用户名或密码不正确');
        }

    }
}