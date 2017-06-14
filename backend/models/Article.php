<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Article extends ActiveRecord{
    static public $status=['1'=>'正常','0'=>'隐藏','-1'=>'删除'];
    public function rules()
    {
        return [
            [['name','intro','article_category_id','sort','status'],'required'],
            [['sort','status','article_category_id'],'integer']
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'文章名',
            'intro'=>'文章简介',
            'article_category_id'=>'文章分类ID',
            'sort'=>'排序',
            'status'=>'状态',
        ];
    }
    //一对一关联表
    public function getGuanLian(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
}