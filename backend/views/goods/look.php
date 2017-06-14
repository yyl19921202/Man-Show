<html>
<head>
    <style>
        .text{
            text-indent: 30px;
        }
    </style>
</head>
<h2><center><?=$looks->name?></center></h2>
<?=\yii\bootstrap\Html::a('返回列表',['@web/goods/index'],['class'=>'btn btn-info btn-xs'])?>
<hr/>
<div class="text"><?=$ready->content?></div>
</html>