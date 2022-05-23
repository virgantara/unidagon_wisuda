<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "skp_item".
 *
 * @property string $id
 * @property string|null $skp_id
 * @property int|null $komponen_kegiatan_id
 * @property string|null $nama
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
 * @property string|null $kode_mk
 * @property string|null $nama_mk
 * @property string|null $jadwal_id
 * @property float|null $sks_mk
 * @property float|null $sks_bkd
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property BkdDosen[] $bkdDosens
 * @property CatatanHarian[] $catatanHarians
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
            [['target_ak', 'target_qty', 'target_mutu', 'target_waktu', 'target_biaya', 'realisasi_ak', 'realisasi_qty', 'realisasi_waktu', 'realisasi_biaya', 'capaian', 'capaian_skp', 'sks_mk', 'sks_bkd'], 'number'],
            [['updated_at', 'created_at','status_simpan'], 'safe'],
            [['id', 'skp_id', 'target_satuan', 'target_waktu_satuan', 'realisasi_satuan', 'realisasi_mutu', 'realisasi_waktu_satuan', 'kode_mk'], 'string', 'max' => 50],
            [['nama'], 'string', 'max' => 255],
            [['nama_mk'], 'string', 'max' => 100],
            [['jadwal_id'], 'string', 'max' => 10],
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
            'id' => Yii::t('app', 'ID'),
            'skp_id' => Yii::t('app', 'SKP'),
            'komponen_kegiatan_id' => Yii::t('app', 'Komponen Kegiatan ID'),
            'nama' => Yii::t('app', 'Nama'),
            'target_ak' => Yii::t('app', 'Target Ak'),
            'target_qty' => Yii::t('app', 'Target Qty'),
            'target_satuan' => Yii::t('app', 'Target Satuan'),
            'target_mutu' => Yii::t('app', 'Target Mutu'),
            'target_waktu' => Yii::t('app', 'Target Waktu'),
            'target_waktu_satuan' => Yii::t('app', 'Target Waktu Satuan'),
            'target_biaya' => Yii::t('app', 'Target Biaya'),
            'realisasi_ak' => Yii::t('app', 'Realisasi Ak'),
            'realisasi_qty' => Yii::t('app', 'Realisasi Qty'),
            'realisasi_satuan' => Yii::t('app', 'Realisasi Satuan'),
            'realisasi_mutu' => Yii::t('app', 'Realisasi Mutu'),
            'realisasi_waktu' => Yii::t('app', 'Realisasi Waktu'),
            'realisasi_waktu_satuan' => Yii::t('app', 'Realisasi Waktu Satuan'),
            'realisasi_biaya' => Yii::t('app', 'Realisasi Biaya'),
            'capaian' => Yii::t('app', 'Capaian'),
            'capaian_skp' => Yii::t('app', 'Capaian Skp'),
            'kode_mk' => Yii::t('app', 'Kode Mk'),
            'nama_mk' => Yii::t('app', 'Nama Mk'),
            'jadwal_id' => Yii::t('app', 'Jadwal ID'),
            'sks_mk' => Yii::t('app', 'Sks Mk'),
            'sks_bkd' => Yii::t('app', 'Sks Bkd'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[BkdDosens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBkdDosens()
    {
        return $this->hasMany(BkdDosen::className(), ['skp_item_id' => 'id']);
    }

    /**
     * Gets query for [[CatatanHarians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCatatanHarians()
    {
        return $this->hasMany(CatatanHarian::className(), ['skp_item_id' => 'id']);
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

    public function hitungSkp()
    {

        $aspek_kuantitas = 0;
        $aspek_kualitas = 0;
        $aspek_waktu = 0;
        $aspek_biaya = 0;
        $nilai_tertimbang = 1.76;

        $pembagi = 2;

        if($this->target_qty > 0)
            $aspek_kuantitas = $this->realisasi_qty / $this->target_qty * 100;

        
        if($this->target_mutu > 0)
            $aspek_kualitas = $this->realisasi_mutu / $this->target_mutu * 100;

        if($this->target_waktu > 0)
        {
            $ew = 100 - ($this->realisasi_waktu / $this->target_waktu * 100);

            if($ew > 24)
            {
                $aspek_waktu = 76-(((($nilai_tertimbang * $this->target_waktu - $this->realisasi_waktu) / $this->target_waktu)*100)-100);
            }

            else
            {
                $aspek_waktu = ($nilai_tertimbang * $this->target_waktu - $this->realisasi_waktu) / $this->target_waktu * 100;
            }

            $pembagi++;
            
        }
        
        if($this->target_biaya > 0) {
            $eb = 100 - ($this->realisasi_biaya / $this->target_biaya * 100);

            if($eb > 24)  {
                $aspek_biaya = 76-(((($nilai_tertimbang * $this->target_biaya - $this->realisasi_biaya) / $this->target_biaya)*100)-100);
            }

            else {
                $aspek_biaya = ($nilai_tertimbang * $this->target_biaya - $this->realisasi_biaya) / $this->target_biaya * 100;
            }

            $pembagi++;
            
        }
        

        $penghitungan = $aspek_kuantitas + $aspek_kualitas + $aspek_waktu + $aspek_biaya;

        $this->capaian = $penghitungan;
        $this->capaian_skp = $penghitungan / $pembagi;
        $this->save();

    }
}
