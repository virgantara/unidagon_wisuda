<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "skp_perilaku".
 *
 * @property string $id
 * @property string|null $skp_id
 * @property float|null $orientasi
 * @property float|null $integritas
 * @property float|null $komitmen
 * @property float|null $disiplin
 * @property float|null $kerjasama
 * @property float|null $kepemimpinan
 * @property float|null $total
 * @property float|null $rata_rata
 * @property string|null $keberatan_pegawai_dinilai
 * @property string|null $keberatan_atasan_penilai
 * @property string|null $keberatan_pejabat_penilai
 * @property string|null $keputusan_atasan_pejabat_atas_keberatan
 * @property string|null $rekomendasi
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property Skp $skp
 */
class SkpPerilaku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skp_perilaku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['orientasi', 'integritas', 'komitmen', 'disiplin', 'kerjasama', 'kepemimpinan', 'total', 'rata_rata'], 'number'],
            [['orientasi', 'integritas', 'komitmen', 'disiplin', 'kerjasama', 'kepemimpinan', 'rata_rata'], 'number','min' => 0,'max'=>100],
            [['keberatan_pegawai_dinilai', 'keberatan_atasan_penilai', 'keberatan_pejabat_penilai', 'keputusan_atasan_pejabat_atas_keberatan', 'rekomendasi'], 'string'],
            [['updated_at', 'created_at'], 'safe'],
            [['id', 'skp_id'], 'string', 'max' => 50],
            [['id'], 'unique'],
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
            'orientasi' => 'Orientasi',
            'integritas' => 'Integritas',
            'komitmen' => 'Komitmen',
            'disiplin' => 'Disiplin',
            'kerjasama' => 'Kerjasama',
            'kepemimpinan' => 'Kepemimpinan',
            'total' => 'Total',
            'rata_rata' => 'Rata Rata',
            'keberatan_pegawai_dinilai' => 'Keberatan Pegawai Dinilai',
            'keberatan_atasan_penilai' => 'Keberatan Atasan Penilai',
            'keberatan_pejabat_penilai' => 'Keberatan Pejabat Penilai',
            'keputusan_atasan_pejabat_atas_keberatan' => 'Keputusan Atasan Pejabat Atas Keberatan',
            'rekomendasi' => 'Rekomendasi',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
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
