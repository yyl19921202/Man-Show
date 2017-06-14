<?php
/**
 * @var $this \yii\web\View
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'parent_id')->hiddenInput();
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($model,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
$zNodes=\yii\helpers\Json::encode($categories);
$js=new \yii\web\JsExpression(
   <<<JS
 var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
    data: {
        simpleData: {
            enable: true,
                 idKey: "id",
                 pIdKey: "parent_id",
                 rootPId: 0
        }
    },
    callback:{
        onClick:function(event,treeId,treeNode){
            $("#goodscategory-parent_id").val(treeNode.id);
        }
    }
};
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes = {$zNodes};
     zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
JS
);
$this->registerJs($js);

