<?php
use yii\web\JsExpression; //图片插件


$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'logo')->hiddenInput();
//echo $form->field($model,'imgFile')->fileInput(['id'=>'test']);
echo \yii\bootstrap\Html::fileInput('test',null,['id'=>'test']);
//插件
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将图片上传成功后的地址（data.fileUrl）写入img标签
        $("#img_logo").attr("src",data.fileUrl).show();
        //将图片上传成功后的地址（data.fileUrl）写入logo标签
        $("#brand-logo").val(data.fileUrl);
    }
}
EOF
            ),
        ]
    ]);
    //图片回显
    if($model->logo){
        echo \yii\bootstrap\Html::img('@web'.$model->logo,['id'=>'img_logo','height'=>60]);
    }else{
        echo \yii\bootstrap\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>60]);
    }

    //if($model->logo) echo \yii\bootstrap\Html::img($model->logo,['width'=>'40px']);
    echo $form->field($model,'sort');
    echo $form->field($model,'status',['inline'=>'true'])->radioList(['1'=>'正常','0'=>'隐藏']);
    echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
    \yii\bootstrap\ActiveForm::end();