<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "setting".
 *
 * @property int $id_post
 * @property string $kode_setting
 * @property string $konten
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kode_setting', 'konten'], 'required'],
            [['konten'], 'string'],
            [['kode_setting'], 'string', 'max' => 255],
            [['kode_setting'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_post' => Yii::t('app', 'Id Post'),
            'kode_setting' => Yii::t('app', 'Kode Setting'),
            'konten' => Yii::t('app', 'Konten'),
        ];
    }
}
