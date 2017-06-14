<?=\yii\bootstrap\Html::a('',['@web/goods/add'],['class'=>'btn btn-warning btn-xs glyphicon glyphicon-plus','style'=>'float:right'])?>
<?php
echo \yii\bootstrap\Html::beginForm(\yii\helpers\Url::to(['goods/index']),'get');
echo '请输入商品名:';
echo \yii\bootstrap\Html::textInput('data');
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info']);
echo \yii\bootstrap\Html::endForm();
?>
<table class="table table-hover table-bordered table-striped">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>logo头像</th>
        <th>所属品牌</th>
        <th>所属商品类型</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>排序</th>
        <th>状态</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goods as $good):?>
    <tr>
        <td><?=$good->id?></td>
        <td><?=$good->name?></td>
        <td><?=$good->sn?></td>
        <td><img src="<?=$good->logo?>" width="50px"></td>
        <td><?=$good->categories? $good->categories->name:''?></td>
        <td><?=$good->bands ? $good->bands->name:''?></td>
        <td><?=$good->makert_price?></td>
        <td><?=$good->shop_price?></td>
        <td><?=$good->stock?></td>
        <td><?=\backend\models\Goods::$is_on_sale[$good->is_on_sale]?></td>
        <td><?=$good->sort?></td>
        <td><?=\backend\models\Goods::$status[$good->status]?></td>
        <td><?=date('Y-m-d H:i:s',$good->create_time)?></td>
        <td>
            <?=\yii\bootstrap\Html::a('',['@web/goods/del','id'=>$good->id],['class'=>'btn btn-danger btn-xs 	glyphicon glyphicon-trash'])?>
            <?=\yii\bootstrap\Html::a('',['@web/goods/edit','id'=>$good->id],['class'=>'btn btn-info btn-xs 	glyphicon glyphicon-pencil'])?>
            <?=\yii\bootstrap\Html::a('',['@web/goods/look','id'=>$good->id],['class'=>'btn btn-success btn-xs 	glyphicon glyphicon-envelope'])?>
            <?=\yii\bootstrap\Html::a('',['@web/img/index','id'=>$good->id],['class'=>'btn btn-primary btn-xs 	glyphicon glyphicon-camera'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>