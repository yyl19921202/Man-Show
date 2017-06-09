<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170608_085749_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('商品分类名'),
            'intro'=>$this->text()->comment('商品分类简介'),
            'sort'=>$this->integer()->comment('商品分类排序'),
            'status'=>$this->smallInteger()->comment('商品分类状态'),
            'is_helep'=>$this->integer()->comment('帮助'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
