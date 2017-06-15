<?php
namespace backend\controllers;
use backend\models\Brand;
use GuzzleHttp\Psr7\UploadedFile;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

use xj\uploadify\UploadAction;
use crazyfd\qiniu\Qiniu;
class BrandController extends Controller
{
    //品牌表的添加
    public function actionAdd()
    {
        //实例化一个对象
        $model = new Brand();
        $request = new Request();
        if ($request->isPost) {//判断传输过来的内容是否已POST方式提交
            $model->load($request->post());//加载数据
//            $model->imgFile = \yii\web\UploadedFile::getInstance($model, 'imgFile');//实例化上传的图片
            if ($model->validate()) {//判断获取到的内容是否符合要求
//                if ($model->imgFile) {//判断图片是否要求
//                    $fileName = '/images/brand/' . uniqid() . '.' . $model->imgFile->extension;//创建图片路径，改名
//                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);//将图片移动到指定位置
//                    $model->logo = $fileName;//图片赋值
//                }
                $model->logo=$this->actionTest($model->logo);
                $model->save();//保存数据
                \Yii::$app->session->setFlash('success', '品牌添加成功');//提示用户添加成功
                return $this->redirect(['brand/index']);//返回首页
            } else {
                var_dump($model->getErrors());
                exit;//打印错误信息
            }
        }

        return $this->render('add', ['model' => $model]);
    }

    //显示首页
    public function actionIndex()
    {
        //分页   总条数   当前显示地几页 每页显示多少条数据
        $zid=Brand::find();//先找到这个表
        $brands = $zid->count();//根据这个表统计里面总共有多少条数据
        $page=new Pagination([//
            'totalCount'=>$brands,//总共数据
            'defaultPageSize'=>3,//每页显示多少条
        ]);
        $brands=$zid->offset($page->offset)->limit($page->limit)->all();//取出每页数据从哪条开始到那条结束
        return $this->render('index', ['brands' => $brands,'page'=>$page]);//返回到首页
    }

    //删除
    public function actionDel($id)
    {
        $model = Brand::findOne(['id' => $id]);//根据id找到商品
        $model->status=-1;
        $model->save();//删除
        \Yii::$app->session->setFlash('success', '删除成功');//提示用户删除成功
        return $this->redirect(['brand/index']);//返回首页
    }

    //修改
    public function actionEdit($id)
    {
        $model = Brand::findOne(['id' => $id]);
        $request = new Request();
        if ($request->isPost) {//判断传输过来的内容是否已POST方式提交
            $model->load($request->post());//加载数据
//            $model->imgFile = \yii\web\UploadedFile::getInstance($model, 'imgFile');//实例化上传的图片
            if ($model->validate()) {//判断获取到的内容是否符合要求
//                if ($model->imgFile) {//判断图片是否要求
//                    $fileName = '/images/brand/' . uniqid() . '.' . $model->imgFile->extension;//创建图片路径，改名
//                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);//将图片移动到指定位置
//                    $model->logo = $fileName;//图片赋值
//                }
                $model->save();//保存数据
                \Yii::$app->session->setFlash('success', '品牌修改成功');//提示用户修改成功
                return $this->redirect(['brand/index']);//返回首页
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //插件
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
//                    $imgUrl=$action->getWebUrl();
                    $action->output['fileUrl'] = $action->getWebUrl();
                    //调用七牛云图片，将图片上传至七牛云
//                   $qiniu= \Yii::$app->qiniu;
//                   $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);
                   //获取该图片在七牛云的地址
//                    $url = $qiniu->getLink($imgUrl);
//                    $action->output['fileUrl'] = $url;

                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],
        ];
    }
    //七牛插件
    public function actionTest($logo){
        $ak = 'lca6v54Qk-QIGDeCMqNKopIdfG3eAYm6q8MnNr9Y';
        $sk = '4VidFByQAjl4DezQWZ-z2absA1lbc8CjD4ejS0Z9';
        $domain = 'http://or9r703mc.bkt.clouddn.com/';
        $bucket = 'man-show';

        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
        //要上传的文件
        $fileName=\Yii::getAlias('@webroot').$logo;
        $key = $logo;
        $qiniu->uploadFile($fileName,$key);
        $url = $qiniu->getLink($key);
        return $url;
    }
}