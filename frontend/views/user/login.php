<?php
use yii\helpers\Html;
?>
    <!-- 登录主体部分start -->
    <div class="login w990 bc mt10 regist">
        <div class="login_hd">
            <h2>用户注册</h2>
            <b></b>
        </div>
        <div class="login_bd">
            <div class="login_form fl">
<?php
//注册表单  不需要使用bootstrap样式，所以使用\yii\widgets\ActiveForm
$form = \yii\widgets\ActiveForm::begin(
    ['fieldConfig'=>[
        'options'=>[
            'tag'=>'li',
        ],
        'errorOptions'=>[
            'tag'=>'p'
        ]
    ]]
);
echo '<ul>';
echo $form->field($model,'username')->textInput(['class'=>'txt']);//用户名
echo $form->field($model,'password_hash')->passwordInput(['class'=>'txt']);//密码
//验证码
//echo $form->field($model,'code',['options'=>['class'=>'checkcode']])->widget(\yii\captcha\Captcha::className(),['template'=>'{input}{image}']);
echo $form->field($model,'remerberMe')->checkbox();
echo '<li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="登陆" />
                    </li>';
echo '</ul>';
\yii\widgets\ActiveForm::end();
?>