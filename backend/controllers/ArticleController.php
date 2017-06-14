<?php
namespace backend\controllers;
use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\web\Controller;
use yii\web\Request;

class ArticleController extends Controller
{
    public function actionAdd()
    {//添加
        $model = new Article();//实例化一个对象
        $models=new ArticleDetail(); //实例化另一个对象
        $request = new Request();
        if ($request->isPost) {//判断用户是否是以post传输
            $model->load($request->post());//加载内容
            $models->load($request->post());//加载内容
            if ($model->validate() && $models->validate()) {//判断内容是否正确
                $model->create_time = time();//保存一个创建的时间戳
                $model->save();//将数据保存起来
                $models->article_id=$model->id;
                $models->save();
                \Yii::$app->session->setFlash('success', '添加成功');//提示信息
                return $this->redirect(['article/index']);
            } else {
                var_dump($model->getErrors());
                exit;//返回错误信息
            }
        }
        $cas=ArticleCategory::find()->all();
        $data=[];
        foreach ($cas as $v){
            $data[$v->id]=$v->name;
        }
        return $this->render('add', ['model' => $model,'models'=>$models,'data'=>$data]);//返回
    }

    public function actionIndex()
    {//显示
        $articles = Article::find()->all();

        return $this->render('index', ['articles' => $articles]);
    }

    public function actionDel($id)
    {//删除
        $model = Article::findOne(['id' => $id]);
        $model->delete();
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['article/index']);
    }

    public function actionEdit($id)
    {//修改
        $model = Article::findOne(['id' => $id]);
        $models=ArticleDetail::findOne(['article_id'=>$id]);
        $request = new Request();
        if ($request->isPost) {//判断用户是否是以post传输
            $model->load($request->post());//加载内容
            $models->load($request->post());//加载内容
            if ($model->validate() && $models->validate()) {//判断内容是否正确
                $model->create_time = time();//保存一个创建的时间戳
                $model->save();//将数据保存起来
                $models->article_id=$model->id;
                $models->save();
                \Yii::$app->session->setFlash('success', '修改成功');//提示信息
                return $this->redirect(['article/index']);
            }
        }
        $cas=ArticleCategory::find()->all();
        $data=[];
        foreach ($cas as $v){
            $data[$v->id]=$v->name;
        }
        return $this->render('add',['model'=>$model,'data'=>$data,'models'=>$models]);
    }
    //文章内容
    public function actionLook($id){
        $looks=ArticleDetail::findOne(['article_id'=>$id]);
        $lookes=Article::findOne(['id'=>$id]);
        return $this->render('look',['looks'=>$looks,'lookes'=>$lookes]);
    }
}