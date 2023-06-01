<?php

use yii\db\Migration;

/**
 * Class m230601_104003_add_column
 */
class m230601_104003_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('peserta', 'approved_at', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230601_104003_add_column cannot be reverted.\n";

        return false;
    }
    */
}
