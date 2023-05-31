<?php

use yii\db\Migration;

/**
 * Class m230528_094443_add_column
 */
class m230528_094443_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('peserta', 'approved_by', $this->integer());
        $this->addColumn('peserta', 'ukuran_kaos', $this->string(3));
        $this->addColumn('peserta', 'jumlah_rombongan', $this->string(3));
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
        echo "m230528_094443_add_column cannot be reverted.\n";

        return false;
    }
    */
}
