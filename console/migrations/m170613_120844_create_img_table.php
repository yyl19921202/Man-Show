<?php

use yii\db\Migration;

/**
 * Handles the creation of table `img`.
 */
class m170613_120844_create_img_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('img', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->comment('所属商品id'),
            'img'=>$this->string(255)->comment('商品相册'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('img');
    }
}
