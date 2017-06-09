<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Brand extends ActiveRecord{
    //定义一个图片字段
//    public $imgFile;

    static public $status=['1'=>'正常','0'=>'隐藏','-1'=>'删除'];

    //设置字段规则
    public function rules()
    {
        return [
          [['name'],'required'],//里面的字段不能为空
          [['intro'],'string'],
          [['sort','status'],'integer'],
          [['logo'],'string','max'=>255],
//            ['imgFile','file','extensions'=>['jpg','png','gif']],//图片上传的规则
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'商品名',
            'intro'=>'商品简介',
            'sort'=>'排序',
            'status'=>'状态',
            'logo'=>'LOGO',
        ];
    }
}