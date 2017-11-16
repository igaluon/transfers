<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transfer`.
 */
class m171017_185416_create_transfer_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%transfer}}', [
            'id' => $this->primaryKey(),
            'username_id' => $this->integer(11),
            'nickname_id' => $this->integer(11),
            'transfer' => $this->float(11),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('id-transfer-username_id', '{{%transfer}}', 'username_id');
        $this->addForeignKey('fk-transfer-username_id-users-id', '{{%transfer}}', 'username_id', '{{%users}}', 'id', 'CASCADE');


    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%transfer}}');
    }
}
