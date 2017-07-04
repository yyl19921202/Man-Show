<?php
namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class Flow3Asset extends AssetBundle
{
    public $jsOptions=array(
        'position'=>View::POS_HEAD
    );
    public $basePath = '@webroot';//静态资源的硬盘路径
    public $baseUrl = '@web';//静态资源的url路径

    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/success.css',
        'style/footer.css',
    ];
    //需要加载的js文件
    public $js = [
    ];
    //和其他静态资源管理器的依赖关系
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}