<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');//商品名称
echo $form->field($model,'imgFile')->fileInput();
echo $model->logo ? "<img src='{$model->logo}' width='50px'>":'';
echo $form->field($model,'brand_id')->dropDownList($brands,['prompt'=>'请选择分类']);
echo $form->field($model,'goods_category_id')->dropDownList($goodscategory,['prompt'=>'请选择分类']);//$data,['prompt'=>'请选择分类']
echo $form->field($model,'makert_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'sort');
echo $form->field($model,'is_on_sale',['inline'=>'true'])->radioList(['1'=>'在售','0'=>'下架']);
echo $form->field($model,'status',['inline'=>'true'])->radioList(['1'=>'正常','0'=>'回收']);
echo $form->field($models,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
