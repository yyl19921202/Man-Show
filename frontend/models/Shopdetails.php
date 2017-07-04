<?php
namespace frontend\models;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Shopdetails extends ActiveRecord{
    public function rules()
    {
        return [
            [['receiver','address','tel','site','site2','site3'],'required'],
            ['tel','integer'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'receiver'=>'收件人',
            'site'=>'所在省',
            'site2'=>'所在市',
            'site3'=>'所在区',
            'address'=>'详细地址',
            'tel'=>'电话号码',
            'status'=>'设置为默认值',
        ];
    }
}