<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Img extends ActiveRecord{
    public $imgFile;
    public function rules()
    {
        return[
            [['goods_id'],'required'],
            ['imgFile','file','extensions'=>['jpg','png','gif']],//图片验证规则
            ];
    }
    public function attributeLabels()
    {
        return [
            'goods_id'=>'商品名称',
            'imgFile'=>'商品图片',
        ];
    }
    //建立关系
    public function getImges(){
        return $this->hasOne(Goods::className(),['id'=>'goods_id']);
    }
}