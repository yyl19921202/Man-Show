<?php
namespace backend\models;
use yii\db\ActiveRecord;

class Goods extends ActiveRecord{
    public $imgFile;
    static public $status=['1'=>'正常','0'=>'回收'];
    static public $is_on_sale=['1'=>'在售','0'=>'下架'];

    public function rules()
    {
        return [
            [['name','goods_category_id','brand_id','imgFile'],'required'],
            [['makert_price','shop_price','stock','is_on_sale','status','sort'],'integer'],
            ['imgFile','file','extensions'=>['jpg','png','gif']],//验证图片规则
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'商品名称',
            'imgFile'=>'logo图片',
            'goods_category_id'=>'商品分类',
            'brand_id'=>'品牌分类',
            'makert_price'=>'市场价格',
            'shop_price'=>'商品价格',
            'stock'=>'库存',
            'is_on_sale'=>'是否在线',
            'status'=>'状态',
            'sort'=>'排序',
        ];
    }
    //建立一对多的关系
    public function getBands(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }

    public function getCategories(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    //建立关系
    public function getImgs(){
        return $this->hasMany(Img::className(),['goods_id'=>'id']);
    }
}