<?php

namespace yuncms\doing\migrations;

use yii\db\Migration;

/**
 * Class M170827042733Create_doing_table
 */
class M170827042733Create_doing_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE  utf8mb4_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%doing}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->unsigned(),
            'action' => $this->string(),
            'model_id' => $this->integer(),
            'model' => $this->string(),
            'subject' => $this->string(),
            'content' => $this->string(),
            'refer_id' => $this->integer(),
            'refer_user_id' => $this->integer(),
            'refer_content' => $this->string(),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('{{%doing_ibfk_1}}', '{{%doing}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

    }

    /**
     * @inheritdoc
     */
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
        echo "M170827042733Create_doing_table cannot be reverted.\n";

        return false;
    }
    */
}
