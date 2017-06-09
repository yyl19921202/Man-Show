<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170608_071646_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            //设置数据库字段
            //名称  字符传类型
            'name'=>$this->string(50)->notNull()->comment('名称'),
            //简介   类型为text
            'intro'=>$this->text()->comment('简介'),
            //logo
            'logo'=>$this->string(255)->comment('logo'),
            //拍序
            'sort'=>$this->integer()->comment('排序'),
            //状态
            'status'=>$this->smallInteger(2)->comment('状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
