<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language'=>'zh-CN',//语言设置
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
       //地址美化
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
//        //七牛插件
//        'qiniu'=>[
//            'class'=>\backend\components\Qiniu::className(),
//            'up_host'=>'http://up-z2.qiniu.com',
//              'accessKey'=>'lca6v54Qk-QIGDeCMqNKopIdfG3eAYm6q8MnNr9Y',
//              'secretKey'=>'VidFByQAjl4DezQWZ-z2absA1lbc8CjD4ejS0Z9',
//              'bucket'=>'hman-show',
//              'domain'=>'ttp://or9r703mc.bkt.clouddn.com/',
//        ]

    ],
    'params' => $params,
];
