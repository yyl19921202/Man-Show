<?php

namespace backend\models;


use Yii;
use creocoder\nestedsets\NestedSetsBehavior;
/**
 * This is the model class for table "Goods_category".
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 * @property integer $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Goods_category';
    }

    //建立关联关系
    public function getParent(){
        return $this->hasOne(self::className(),['id'=>'parent_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['name','parent_id'],'required'],
            [['tree', 'lft', 'rgt',  'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
            //名字不能重复
//            ['name','unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'tree' => '树id',
            'lft' => '左值',
            'rgt' => '右值',
            'depth' => '层级',
            'name' => '名称',
            'parent_id' => '上级分类id',
            'intro' => '简介',
        ];
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                 'treeAttribute' => 'tree',//必须打开才能够添加多颗树
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }

}
