<?=\yii\bootstrap\Html::a('添加图片',['@web/img/add'],['class'=>'btn btn-success btn-xs'])?>
<?=\yii\bootstrap\Html::a('返回列表',['@web/goods/index'],['class'=>'btn btn-info btn-xs'])?>
<?php foreach ($imgs as $img):?>
<div>
    <img src="<?=$img->img?>" style="margin:10px;width:160px;height:100px;float: left" >
</div>
<?php endforeach;?>
