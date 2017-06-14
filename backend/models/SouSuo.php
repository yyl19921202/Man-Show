<?php
namespace backend\models;
use yii\base\Model;

class SouSuo extends Model {
    public $name;
    public function rules()
    {
        return [
            ['name','required'],
        ];
    }
}