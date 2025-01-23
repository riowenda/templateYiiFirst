<?php

use yii\db\Migration;

/**
 * Class m241007_043006_up_tbl_authItem
 */
class m241007_043006_up_tbl_authItem extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //add kolom menu_id
        $this->addColumn('auth_item', 'menu_id', $this->integer()->null());

        //create table auth_menu
        $this->createTable('auth_menu', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241007_043006_up_tbl_authItem cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241007_043006_up_tbl_authItem cannot be reverted.\n";

        return false;
    }
    */
}
