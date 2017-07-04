<?php
namespace frontend\models;
use yii\db\ActiveRecord;

class GoodsCategory extends ActiveRecord{
    public function refresh()
    {
        return [];
    }
    public function getParent(){
        return $this->hasOne(self::className(),['id'=>'parent_id']);
    }
}