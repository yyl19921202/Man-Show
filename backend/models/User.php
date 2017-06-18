<?php
namespace backend\models;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface{
    public $code;
    public $roles;
    public function rules()
    {
        return [
            [['username','password_hash','email'],'required'],
            ['code','captcha','captchaAction'=>'user/captcha'],
            ['username','unique'],
//            ['email','match','pattern'=>'/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/','message'=>'邮箱格式不正确'],
            ['email', 'unique'],
            ['email', 'email'],
            ['roles','safe'],
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
    //用户和角色的关联
    public static function getRole(){
        //实力话对象
        $authManager=\Yii::$app->authManager;
        return ArrayHelper::map($authManager->getRoles(),'name','description');
    }
    //关联用户和角色
    public function addRole(){
        //实例化对象
        $authManager=\Yii::$app->authManager;
//        $this->validate($this->roles);exit;
        foreach ($this->roles as $roleDescription){
            $role=$authManager->getRole($roleDescription);
            if($role)$authManager->assign($role,$this->id);
        }
    }

    public function beforeSave($insert)
    {
        if($insert){
            $this->auth_key=\Yii::$app->security->generateRandomString();//自动随机生成字符串
        }

        return parent::beforeSave($insert);
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    //下面2个都是实现自动登录的方法
    public function getAuthKey()
    {
        return $this->auth_key;
        // TODO: Implement getAuthKey() method.
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() == $authKey;
        // TODO: Implement validateAuthKey() method.
    }
}