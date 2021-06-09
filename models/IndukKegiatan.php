<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "induk_kegiatan".
 *
 * @property int $id
 * @property string $nama
 *
 * @property UnsurKegiatan[] $unsurKegiatans
 */
class IndukKegiatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'induk_kegiatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['nama'], 'string', 'max' => 255],
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
     * Gets query for [[UnsurKegiatans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnsurKegiatans()
    {
        return $this->hasMany(UnsurKegiatan::className(), ['induk_id' => 'id']);
    }
}
