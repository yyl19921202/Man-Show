<?=\yii\bootstrap\Html::a('添加商品',['@web/brand/add'],['class'=>'btn btn-warning'])?>
<table class="table table-hover table-bordered text-capitalize">
    <tr>
        <th>ID</th>
        <th>商品名</th>
        <th>排序</th>
        <th>状态</th>
        <th>商品LOGO</th>
        <th>商品简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brands as $brand):?>
    <tr>
        <td><?=$brand->id?></td>
        <td><?=$brand->name?></td>
        <td><?=$brand->sort?></td>
        <td><?=\backend\models\Brand::$status[$brand->status]?></td>
        <td><img src="<?=$brand->logo?>" width="40px"></td>
        <td><?=$brand->intro?></td>
        <td>
            <?=\yii\bootstrap\Html::a('删除',['@web/brand/del','id'=>$brand->id],['class'=>'btn btn-danger btn-xs'])?>
            <?=\yii\bootstrap\Html::a('修改',['@web/brand/edit','id'=>$brand->id],['class'=>'btn btn-info btn-xs'])?>
        </td>
    </tr>
    <?php endForeach;?>
</table>
<?php
echo  \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',
]);
