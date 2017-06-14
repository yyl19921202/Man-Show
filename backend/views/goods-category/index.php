<?=\yii\bootstrap\Html::a('添加',['@web/goods-category/add'],['class'=>'btn btn-warning btn-xs'])?>
<table class="table table-bordered table-hover table-condensed">
    <tr>
        <th>ID</th>
        <th>name</th>
        <th>parent_id</th>
        <th>intro</th>
        <th>操作</th>
    </tr>
    <?php foreach ($goods as $good):?>
    <tr>
        <td><?=$good->id?></td>
        <td><?=$good->name?></td>
        <td><?=$good->parent_id ? $good->parent->name:''?></td>
        <td><?=$good->intro?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['@web/goods-category/edit','id'=>$good->id],['class'=>'btn btn-info btn-xs'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
