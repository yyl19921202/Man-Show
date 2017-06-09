<?=\yii\bootstrap\Html::a('添加商品',['@web/article-category/add'],['class'=>'btn btn-warning'])?>
<table class="table table-hover table-bordered text-capitalize">
    <tr>
        <th>ID</th>
        <th>商品分类名</th>
        <th>排序</th>
        <th>状态</th>
        <th>商品简介</th>
        <th>是否帮助</th>
        <th>操作</th>
    </tr>
    <?php foreach ($acs as $ac):?>
        <tr>
            <td><?=$ac->id?></td>
            <td><?=$ac->name?></td>
            <td><?=$ac->sort?></td>
            <td><?=\backend\models\ArticleCategory::$status[$ac->status]?></td>
            <td><?=$ac->intro?></td>
            <td><?=\backend\models\ArticleCategory::$help[$ac->is_helep]?></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['@web/article-category/del','id'=>$ac->id],['class'=>'btn btn-danger btn-xs'])?>
                <?=\yii\bootstrap\Html::a('修改',['@web/article-category/edit','id'=>$ac->id],['class'=>'btn btn-info btn-xs'])?>
            </td>
        </tr>
    <?php endForeach;?>
</table>