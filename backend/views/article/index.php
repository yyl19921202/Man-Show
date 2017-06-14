<h2>文章列表</h2>
<?=\yii\bootstrap\Html::a('添加文章',['@web/article/add'],['class'=>'btn btn-warning'])?>
<table class="table table-hover table-bordered text-capitalize">
    <tr>
        <th>ID</th>
        <th>文章名</th>
        <th>简介</th>
        <th>所属分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article):?>
        <tr>
            <td><?=$article->id?></td>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->guanLian->name?></td>
            <td><?=$article->sort?></td>
            <td><?=\backend\models\ArticleCategory::$status[$article->status]?></td>
            <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('删除',['@web/article/del','id'=>$article->id],['class'=>'btn btn-danger btn-xs'])?>
                <?=\yii\bootstrap\Html::a('修改',['@web/article/edit','id'=>$article->id],['class'=>'btn btn-info btn-xs'])?>
                <?=\yii\bootstrap\Html::a('查看',['@web/article/look','id'=>$article->id],['class'=>'btn btn-success btn-xs'])?>
            </td>
        </tr>
    <?php endForeach;?>
</table>