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
            echo $form->field($model,'userpassword')->passwordInput(['class'=>'txt']);//密码
            echo $form->field($model,'email')->textInput(['class'=>'txt']);//邮箱
            echo $form->field($model,'tel')->textInput(['class'=>'txt']);//电话

            $button =  Html::button('发送短信验证码',['id'=>'send_sms_button']);
            echo $form->field($model,'smsCode',['options'=>['class'=>'checkcode'],'template'=>"{label}\n{input}$button\n{hint}\n{error}"])->textInput(['class'=>'txt']);
            //验证码
//            echo $form->field($model,'code',['options'=>['class'=>'checkcode']])->widget(\yii\captcha\Captcha::className(),['template'=>'{input}{image}']);
            echo
                    '<li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn">
                    </li>';
            echo '</ul>';
            \yii\widgets\ActiveForm::end();
?>
<?php
/**
 * @var \yii\web\View
 */
$url=\yii\helpers\Url::to(['user/send-sms']);
$this->registerJS(new \yii\web\JsExpression(
        <<<JS
    $("#send_sms_button").click(function(){//找到要发送短信的节点，设置一个点击事件
      var tel=$("#member-tel").val();//获取手机号码
      // console.log(tel);
      //通过post发送ajax请求
      $.post('$url',{tel:tel},function (data){
        if(data=='success'){
            alert('短信发送成功');
        }else{
            alert(data);
        }
      })
    })
JS
));
