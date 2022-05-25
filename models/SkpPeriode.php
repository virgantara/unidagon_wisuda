<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "skp_periode".
 *
 * @property int $tahun_id
 * @property string $nama_periode
 * @property string|null $tanggal_skp_awal
 * @property string|null $tanggal_skp_akhir
 * @property string|null $buka
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property Skp[] $skps
 */
class SkpPeriode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skp_periode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun_id', 'nama_periode'], 'required'],
            [['tahun_id'], 'integer'],
            [['tanggal_skp_awal', 'tanggal_skp_akhir', 'updated_at', 'created_at'], 'safe'],
            [['nama_periode'], 'string', 'max' => 255],
            [['buka'], 'string', 'max' => 1],
            [['tahun_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tahun_id' => 'Tahun ID',
            'nama_periode' => 'Nama Periode',
            'tanggal_skp_awal' => 'Tanggal Skp Awal',
            'tanggal_skp_akhir' => 'Tanggal Skp Akhir',
            'buka' => 'Buka',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Skps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkps()
    {
        return $this->hasMany(Skp::className(), ['periode_id' => 'tahun_id']);
    }
}
