<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "periode".
 *
 * @property int $id_periode
 * @property string $nama_periode
 * @property string $tahun
 * @property string $tanggal_buka
 * @property string $tanggal_tutup
 * @property string $status_aktivasi
 *
 * @property Peserta[] $pesertas
 */
class Periode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periode';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_periode', 'tahun', 'tanggal_buka', 'tanggal_tutup'], 'required'],
            [['tanggal_buka', 'tanggal_tutup'], 'safe'],
            [['nama_periode'], 'string', 'max' => 255],
            [['tahun'], 'string', 'max' => 50],
            [['status_aktivasi'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_periode' => Yii::t('app', 'Id Periode'),
            'nama_periode' => Yii::t('app', 'Nama Periode'),
            'tahun' => Yii::t('app', 'Tahun'),
            'tanggal_buka' => Yii::t('app', 'Tanggal Buka'),
            'tanggal_tutup' => Yii::t('app', 'Tanggal Tutup'),
            'status_aktivasi' => Yii::t('app', 'Status Aktivasi'),
        ];
    }

    /**
     * Gets query for [[Pesertas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPesertas()
    {
        return $this->hasMany(Peserta::className(), ['periode_id' => 'id_periode']);
    }
}
