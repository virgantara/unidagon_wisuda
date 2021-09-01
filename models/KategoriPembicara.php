<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kategori_pembicara".
 *
 * @property int $id
 * @property string|null $nama
 *
 * @property OrasiIlmiah[] $orasiIlmiahs
 */
class KategoriPembicara extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kategori_pembicara';
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
     * Gets query for [[OrasiIlmiahs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrasiIlmiahs()
    {
        return $this->hasMany(OrasiIlmiah::className(), ['id_kategori_pembicara' => 'id']);
    }
}
