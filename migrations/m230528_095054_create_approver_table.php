<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%approver}}`.
 */
class m230528_095054_create_approver_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%approver}}', [
            'id' => $this->primaryKey(),
            'nama' => $this->string(150),
        ]);

        $this->addForeignKey('approver_peserta_approved_by', 'peserta', 'approved_by', 'approver', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%approver}}');
    }
}
