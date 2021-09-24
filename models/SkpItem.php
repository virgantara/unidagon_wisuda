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
            [['id','nama','komponen_kegiatan_id'], 'required'],
            [['komponen_kegiatan_id'], 'integer'],
            [['target_ak', 'target_qty', 'target_mutu', 'target_waktu', 'target_biaya', 'realisasi_ak', 'realisasi_qty', 'realisasi_waktu', 'realisasi_biaya', 'capaian', 'capaian_skp'], 'number'],
            [['id', 'skp_id', 'target_satuan', 'target_waktu_satuan', 'realisasi_satuan', 'realisasi_mutu', 'realisasi_waktu_satuan'], 'string', 'max' => 50],
            [['nama'], 'string', 'max' => 255],
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
            'skp_id' => 'SKP',
            'komponen_kegiatan_id' => 'Komponen Kegiatan',
            'nama' => 'Nama Kegiatan',
            'target_ak' => 'Target AK',
            'target_qty' => 'Target Qty',
            'target_satuan' => 'Target Satuan',
            'target_mutu' => 'Target Mutu',
            'target_waktu' => 'Target Waktu',
            'target_waktu_satuan' => 'Target Waktu Satuan',
            'target_biaya' => 'Target Biaya',
            'realisasi_ak' => 'Realisasi AK',
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
        
        if($this->target_biaya > 0)
        {
            $eb = 100 - ($this->realisasi_biaya / $this->target_biaya * 100);

            if($eb > 24)
            {
                $aspek_biaya = 76-(((($nilai_tertimbang * $this->target_biaya - $this->realisasi_biaya) / $this->target_biaya)*100)-100);
            }

            else
            {
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
