<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "skp".
 *
 * @property string $id
 * @property string|null $pejabat_penilai
 * @property string|null $pegawai_dinilai
 * @property string|null $atasan_pejabat_penilai
 * @property int|null $jabatan_penilai_id
 * @property int|null $jabatan_pegawai_id
 * @property int|null $jabatan_atasan_penilai_id
 * @property int|null $periode_id
 * @property string|null $status_skp 1=diajukan,2=disetujui,3=ditolak
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property User $pegawaiDinilai
 * @property User $pejabatPenilai
 * @property BkdPeriode $periode
 * @property Jabatan $jabatanPegawai
 * @property Jabatan $jabatanPenilai
 * @property User $atasanPejabatPenilai
 * @property Jabatan $jabatanAtasanPenilai
 * @property SkpItem[] $skpItems
 * @property SkpPerilaku[] $skpPerilakus
 */
class Skp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['jabatan_penilai_id', 'jabatan_pegawai_id', 'jabatan_atasan_penilai_id', 'periode_id'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['id'], 'string', 'max' => 50],
            [['pejabat_penilai', 'pegawai_dinilai', 'atasan_pejabat_penilai'], 'string', 'max' => 15],
            [['pegawai_dinilai', 'jabatan_pegawai_id', 'periode_id'], 'unique', 'targetAttribute' => ['pegawai_dinilai', 'jabatan_pegawai_id', 'periode_id'],'message' => 'SKP sudah dibuat sebelumnya'],
            [['status_skp'], 'string', 'max' => 1],
            [['id'], 'unique'],
            [['pegawai_dinilai'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['pegawai_dinilai' => 'NIY']],
            [['pejabat_penilai'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['pejabat_penilai' => 'NIY']],
            [['periode_id'], 'exist', 'skipOnError' => true, 'targetClass' => SkpPeriode::className(), 'targetAttribute' => ['periode_id' => 'tahun_id']],
            [['jabatan_pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jabatan::className(), 'targetAttribute' => ['jabatan_pegawai_id' => 'ID']],
            [['jabatan_penilai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jabatan::className(), 'targetAttribute' => ['jabatan_penilai_id' => 'ID']],
            [['atasan_pejabat_penilai'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['atasan_pejabat_penilai' => 'NIY']],
            [['jabatan_atasan_penilai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jabatan::className(), 'targetAttribute' => ['jabatan_atasan_penilai_id' => 'ID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pejabat_penilai' => 'Pejabat Penilai',
            'pegawai_dinilai' => 'Pegawai Dinilai',
            'atasan_pejabat_penilai' => 'Atasan Pejabat Penilai',
            'jabatan_penilai_id' => 'Jabatan Penilai',
            'jabatan_pegawai_id' => 'Jabatan Pegawai',
            'jabatan_atasan_penilai_id' => 'Jabatan Atasan Penilai',
            'periode_id' => 'Periode Pengisian SKP',
            'status_skp' => 'Status Skp',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[PegawaiDinilai]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiDinilai()
    {
        return $this->hasOne(User::className(), ['NIY' => 'pegawai_dinilai']);
    }

    /**
     * Gets query for [[PejabatPenilai]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPejabatPenilai()
    {
        return $this->hasOne(User::className(), ['NIY' => 'pejabat_penilai']);
    }

    /**
     * Gets query for [[Periode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriode()
    {
        return $this->hasOne(SkpPeriode::className(), ['tahun_id' => 'periode_id']);
    }

    /**
     * Gets query for [[JabatanPegawai]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJabatanPegawai()
    {
        return $this->hasOne(Jabatan::className(), ['ID' => 'jabatan_pegawai_id']);
    }

    /**
     * Gets query for [[JabatanPenilai]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJabatanPenilai()
    {
        return $this->hasOne(Jabatan::className(), ['ID' => 'jabatan_penilai_id']);
    }

    /**
     * Gets query for [[AtasanPejabatPenilai]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAtasanPejabatPenilai()
    {
        return $this->hasOne(User::className(), ['NIY' => 'atasan_pejabat_penilai']);
    }

    /**
     * Gets query for [[JabatanAtasanPenilai]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJabatanAtasanPenilai()
    {
        return $this->hasOne(Jabatan::className(), ['ID' => 'jabatan_atasan_penilai_id']);
    }

    /**
     * Gets query for [[SkpItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkpItems()
    {
        return $this->hasMany(SkpItem::className(), ['skp_id' => 'id']);
    }

    public function getSumCapaian()
    {
        $total = 0;
        foreach($this->skpItems as $item){
            $total += $item->capaian;
        }

        return $total;
    }

    public function getSumCapaianSkp()
    {
        $total = 0;
        foreach($this->skpItems as $item){
            $total += $item->capaian_skp;
        }

        return $total;
    }

    public function getAvgCapaianSkp()
    {
        $capaian_total = 0;
        $counter=0;
        foreach($this->skpItems as $item){
            $counter++;

            $penghitungan = $item->capaian;
            $capaian_skp = $item->capaian_skp;
            $capaian_total += $capaian_skp;
        }

        $avg_capaian_skp = 0;
        if($counter> 0)
            $avg_capaian_skp = $capaian_total / $counter;

        return $avg_capaian_skp;
    }

    /**
     * Gets query for [[SkpPerilakus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkpPerilaku()
    {
        return $this->hasOne(SkpPerilaku::className(), ['skp_id' => 'id']);
    }
}
