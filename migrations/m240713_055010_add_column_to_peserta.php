<?php

use yii\db\Migration;

/**
 * Class m240713_055010_add_column_to_peserta
 */
class m240713_055010_add_column_to_peserta extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('peserta', 'catatan', $this->string('255'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240713_055010_add_column_to_peserta cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240713_055010_add_column_to_peserta cannot be reverted.\n";

        return false;
    }
    */
}
