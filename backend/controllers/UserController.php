<?php
namespace backend\controllers;
use backend\models\LoginForm;
use backend\models\User;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\rbac\Role;
use yii\web\Controller;
use yii\web\Request;

class UserController extends Controller{
    //验证码
    public function actions(){
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,//验证码最小长度
                'maxLength'=>4,//最大长度
            ],
        ];
    }
    //添加表单 注册功能
    public function actionAdd(){
        $model=new User();//实例化一个对象
        $request=new Request();
        if($request->isPost){//判断传输方式
            $model->load($request->post());//加载内容
            if($model->validate()){
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save(false);
                //用户给角色关联
                $model->addRole();
                //实例化对象
                \Yii::$app->session->setFlash('success','恭喜你，注册成功！');
                return $this->redirect(['user/login']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //显示用户列表并且分页
    public function actionIndex(){
        //需要条件：总共多少条数据     当前显示第几页   每页显示多少条数据
        $zid=User::find();//先找到这个数据表    /搜索页也需要这句

        //搜索功能
        if($data=\Yii::$app->request->get('data')){//接收用户传过来的数据，
            $zid->andWhere(['like','username',$data]);
        }
        //分页
        $models=$zid->count();//找到表之后，统计表里面总共有多少条数据
        $page=new Pagination([//实例化一个对象
            'totalCount'=>$models,//总共有多少条数据
            'defaultPageSize'=>3,//每页显示几条数据
        ]);
       $models=$zid->offset($page->offset)->limit($page->limit)->all();//从哪条数据开始取到哪条结束
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
    //删除功能
    public function actionDel($id){
        $model=User::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['user/index']);
    }
    //修改
    public function actionEdit($id){
        $model=User::findOne(['id'=>$id]);
        $request=new Request();
        if($request->isPost){//判断传输方式
            $model->load($request->post());//加载内容
            if($model->validate()){
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->save(false);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //登陆
    public function actionLogin(){
        $model=new LoginForm();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());//加载内容
            if($model->validate()){
            \Yii::$app->session->setFlash('success','登陆成功');
            return $this->redirect(['user/index']);
            }

        }
        return $this->render('login',['model'=>$model]);
    }
    //注销
    public function actionLoginout(){
        \Yii::$app->user->logout();
        return $this->redirect(['user/login']);
    }


    //防止直接输入主页没有经过登陆可以进入的方法
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' =>[
                    [
                        'allow' => true,
                        'actions' => ['login','captcha','add'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['add','edit','del','index','loginout','login'],
                        'roles' => ['@'],
                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['login'],
//                        'roles' => ['@'],
//                    ]
                ]
            ]
        ];
    }
}