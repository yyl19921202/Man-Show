<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class ListAsset extends AssetBundle{
    public $basePath = '@webroot';//静态资源的硬盘路径
    public $baseUrl = '@web';//静态资源的url路径
    //需要加载的css文件
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/list.css',
        'style/common.css',
        'style/bottomnav.css',
        'style/footer.css',
    ];
    //需要加载的js文件
    public $js = [
        "js/list.js",
        "js/header.js",
        "js/jquery-1.8.3.min.js"

    ];
    //和其他静态资源管理器的依赖关系
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}