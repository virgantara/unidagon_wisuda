<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jenis_tes".
 *
 * @property int $id
 * @property string|null $nama
 *
 * @property Tes[] $tes
 */
class JenisTes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jenis_tes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
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
     * Gets query for [[Tes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTes()
    {
        return $this->hasMany(Tes::className(), ['id_jenis_tes' => 'id']);
    }
}
