<?php

namespace yuncms\doing\migrations;

use yii\db\Migration;

class M171204084950Create_doing_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE  utf8mb4_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%doing}}', [
            'id' => $this->primaryKey()->unsigned()->comment('Id'),
            'user_id' => $this->integer()->unsigned()->comment('User Id'),
            'action' => $this->string(100)->comment('Action'),
            'model_id' => $this->integer()->unsigned()->comment('Model Id'),
            'model' => $this->string(100)->comment('Model'),
            'subject' => $this->string(100)->comment('Subject'),
            'content' => $this->string()->comment('Content'),
            'refer_id' => $this->integer()->comment('Refer Id'),
            'refer_user_id' => $this->integer()->unsigned()->comment('Refer User Id'),
            'refer_content' => $this->string()->comment('Refer Content'),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('Created At'),
        ], $tableOptions);

        $this->addForeignKey('{{%doing_fk_1}}', '{{%doing}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('{{%doing}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171204084950Create_doing_table cannot be reverted.\n";

        return false;
    }
    */
}
