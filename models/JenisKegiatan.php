<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jenis_kegiatan".
 *
 * @property string $id
 * @property string $nama
 *
 * @property Pengabdian[] $pengabdians
 */
class JenisKegiatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jenis_kegiatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nama'], 'required'],
            [['id'], 'string', 'max' => 1],
            [['nama'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
        ];
    }

    /**
     * Gets query for [[Pengabdians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengabdians()
    {
        return $this->hasMany(Pengabdian::className(), ['jenis_kegiatan' => 'id']);
    }
}
