<?php
namespace backend\models;
use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord{
    //保存一个静态变量
    static public $help=['1'=>'是','0'=>'否'];
    static public $status=['1'=>'正常','0'=>'隐藏','-1'=>'删除'];
    public function rules()
    {
        return [
            [['name'], 'required'],//里面的字段不能为空
            [['intro'], 'string'],
            [['sort', 'status', 'is_helep'], 'integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'商品列表名',
            'intro'=>'商品简介',
            'sort'=>'排序',
            'status'=>'状态',
            'is_helep'=>'帮助',
        ];
    }
}