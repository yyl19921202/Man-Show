<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'goods_id')->dropDownList($goods,['prompt'=>'请选择分类']);
echo $form->field($model,'imgFile')->fileInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();