<html>
<head>
    <style>
        #con{
            text-indent: 30px;
        }
    </style>
</head>
<h2><center><?=$lookes->name?></center></h2>
<?=\yii\bootstrap\Html::a('修改',['@web/article/edit','id'=>$lookes->id],['class'=>'btn btn-info btn-xs'])?>&nbsp;
<?=\yii\bootstrap\Html::a('返回列表',['@web/article/index'],['class'=>'btn btn-info btn-xs'])?>
<hr/>
<div>文章发表于:<?=date('Y-m-d H:i:s',$lookes->create_time)?></div>
<br/>
<div id="con"><?=$looks->content?></div>
</html>
