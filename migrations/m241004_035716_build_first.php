<?php

use yii\db\Migration;

/**
 * Class m241004_035716_build_first
 */
class m241004_035716_build_first extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //membuat table user yii2
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_reset_token' => $this->string(),
            'password' => $this->text()->notNull(),
            'email' => $this->string(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
        ]);

        //table log login
        $this->createTable('log_login',[
            'id' => $this->primaryKey(),
            'user_id'=> $this->integer(),
            'login_time'=> $this->datetime()->defaultExpression('CURRENT_TIMESTAMP'),
            'ip_address'=> $this->string(),
            'user_agent'=> $this->string(),
            'status'=> $this->smallInteger(),
        ]);

        //table log crud
        $this->createTable('log_crud',[
            'id' => $this->primaryKey(),
            'table'=> $this->string(50),
            'action'=> $this->string(50),
            'detail'=> $this->text(),
            'user_id'=> $this->integer(),
            'ip_address'=> $this->string(),
            'created_at'=> $this->datetime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        //membuat authoruzation di yii2

        $this->createTable('auth_item', [
            'name' => $this->string(64)->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY(name)',
        ]);

        $this->createTable('auth_item_child', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY(parent, child)',
            'FOREIGN KEY (parent) REFERENCES auth_item (name) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY (child) REFERENCES auth_item (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ]);

        $this->createTable('auth_assignment', [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY(item_name, user_id)',
            'FOREIGN KEY (item_name) REFERENCES auth_item (name) ON DELETE CASCADE ON UPDATE CASCADE',
        ]);

        $this->createTable('auth_rule', [
            'name' => $this->string(64)->notNull(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY(name)',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241004_035716_build_first cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241004_035716_build_first cannot be reverted.\n";

        return false;
    }
    */
}
