<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "skp_item".
 *
 * @property string $id
 * @property string|null $skp_id
 * @property int|null $komponen_kegiatan_id
 * @property float|null $target_ak
 * @property float|null $target_qty
 * @property string|null $target_satuan
 * @property float|null $target_mutu
 * @property float|null $target_waktu
 * @property string|null $target_waktu_satuan
 * @property float|null $target_biaya
 * @property float|null $realisasi_ak
 * @property float|null $realisasi_qty
 * @property string|null $realisasi_satuan
 * @property string|null $realisasi_mutu
 * @property float|null $realisasi_waktu
 * @property string|null $realisasi_waktu_satuan
 * @property float|null $realisasi_biaya
 * @property float|null $capaian
 * @property float|null $capaian_skp
 *
 * @property KomponenKegiatan $komponenKegiatan
 * @property Skp $skp
 */
class SkpItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skp_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['komponen_kegiatan_id'], 'integer'],
            [['target_ak', 'target_qty', 'target_mutu', 'target_waktu', 'target_biaya', 'realisasi_ak', 'realisasi_qty', 'realisasi_waktu', 'realisasi_biaya', 'capaian', 'capaian_skp'], 'number'],
            [['id', 'skp_id', 'target_satuan', 'target_waktu_satuan', 'realisasi_satuan', 'realisasi_mutu', 'realisasi_waktu_satuan'], 'string', 'max' => 50],
            [['id'], 'unique'],
            [['komponen_kegiatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => KomponenKegiatan::className(), 'targetAttribute' => ['komponen_kegiatan_id' => 'id']],
            [['skp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Skp::className(), 'targetAttribute' => ['skp_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'skp_id' => 'Skp ID',
            'komponen_kegiatan_id' => 'Kegiatan',
            'target_ak' => 'Angka Kredit',
            'target_qty' => 'Kuant/Output',
            'target_satuan' => 'Satuan',
            'target_mutu' => 'Kual/Mutu',
            'target_waktu' => 'Waktu',
            'target_waktu_satuan' => 'Waktu Satuan',
            'target_biaya' => 'Biaya',
            'realisasi_ak' => 'Realisasi Ak',
            'realisasi_qty' => 'Realisasi Qty',
            'realisasi_satuan' => 'Realisasi Satuan',
            'realisasi_mutu' => 'Realisasi Mutu',
            'realisasi_waktu' => 'Realisasi Waktu',
            'realisasi_waktu_satuan' => 'Realisasi Waktu Satuan',
            'realisasi_biaya' => 'Realisasi Biaya',
            'capaian' => 'Capaian',
            'capaian_skp' => 'Capaian Skp',
        ];
    }

    /**
     * Gets query for [[KomponenKegiatan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKomponenKegiatan()
    {
        return $this->hasOne(KomponenKegiatan::className(), ['id' => 'komponen_kegiatan_id']);
    }

    /**
     * Gets query for [[Skp]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkp()
    {
        return $this->hasOne(Skp::className(), ['id' => 'skp_id']);
    }
}
