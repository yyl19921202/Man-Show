<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'article_category_id')->dropDownList($data,['prompt'=>'请选择分类']);
echo $form->field($model,'sort');
echo $form->field($models,'content')->textarea();
echo $form->field($model,'status',['inline'=>'true'])->radioList(['1'=>'正常','0'=>'隐藏']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
$form=\yii\bootstrap\ActiveForm::end();
