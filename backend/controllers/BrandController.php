<?php
namespace backend\controllers;
use backend\models\Brand;
use GuzzleHttp\Psr7\UploadedFile;
use yii\web\Controller;
use yii\web\Request;

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
            $model->imgFile = \yii\web\UploadedFile::getInstance($model, 'imgFile');//实例化上传的图片
            if ($model->validate()) {//判断获取到的内容是否符合要求
                if ($model->imgFile) {//判断图片是否要求
                    $fileName = '/images/brand/' . uniqid() . '.' . $model->imgFile->extension;//创建图片路径，改名
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);//将图片移动到指定位置
                    $model->logo = $fileName;//图片赋值
                }
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
        $brands = Brand::find()->all();//寻找到数据
        return $this->render('index', ['brands' => $brands]);//返回到首页
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
            $model->imgFile = \yii\web\UploadedFile::getInstance($model, 'imgFile');//实例化上传的图片
            if ($model->validate()) {//判断获取到的内容是否符合要求
                if ($model->imgFile) {//判断图片是否要求
                    $fileName = '/images/brand/' . uniqid() . '.' . $model->imgFile->extension;//创建图片路径，改名
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);//将图片移动到指定位置
                    $model->logo = $fileName;//图片赋值
                }
                $model->save();//保存数据
                \Yii::$app->session->setFlash('success', '品牌修改成功');//提示用户修改成功
                return $this->redirect(['brand/index']);//返回首页
            }
        }
        return $this->render('add',['model'=>$model]);
    }
}