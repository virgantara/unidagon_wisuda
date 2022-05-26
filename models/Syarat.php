<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "syarat".
 *
 * @property int $id
 * @property string $nama
 * @property string $is_aktif
 */
class Syarat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'syarat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 200],
            [['is_aktif'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nama' => Yii::t('app', 'Nama'),
            'is_aktif' => Yii::t('app', 'Is Aktif'),
        ];
    }
}
