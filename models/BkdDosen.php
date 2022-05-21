<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bkd_dosen".
 *
 * @property int $id
 * @property int $tahun_id
 * @property int $dosen_id
 * @property int|null $komponen_id
 * @property string|null $rencana
 * @property string|null $realisasi
 * @property float|null $sks
 * @property float|null $sks_mk
 * @property string|null $kode_mk
 * @property string|null $nama_mk
 * @property float|null $sks_pak
 * @property string $kondisi
 * @property string|null $skp_item_id
 * @property string|null $deskripsi
 * @property string|null $status_bkd 0=belum selesai,1=selesai,2=berlanjut,3=gagal
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property SkpItem $skpItem
 * @property KomponenKegiatan $komponen
 */
class BkdDosen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bkd_dosen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun_id', 'dosen_id', 'kondisi'], 'required'],
            [['tahun_id', 'dosen_id', 'komponen_id'], 'integer'],
            [['sks', 'sks_mk', 'sks_pak'], 'number'],
            [['deskripsi'], 'string'],
            [['updated_at', 'created_at'], 'safe'],
            [['rencana', 'realisasi'], 'string', 'max' => 255],
            [['kode_mk', 'skp_item_id'], 'string', 'max' => 50],
            [['nama_mk', 'kondisi'], 'string', 'max' => 100],
            [['status_bkd'], 'string', 'max' => 1],
            [['tahun_id', 'dosen_id', 'komponen_id', 'kondisi'], 'unique', 'targetAttribute' => ['tahun_id', 'dosen_id', 'komponen_id', 'kondisi']],
            [['skp_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => SkpItem::className(), 'targetAttribute' => ['skp_item_id' => 'id']],
            [['komponen_id'], 'exist', 'skipOnError' => true, 'targetClass' => KomponenKegiatan::className(), 'targetAttribute' => ['komponen_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tahun_id' => Yii::t('app', 'Tahun ID'),
            'dosen_id' => Yii::t('app', 'Dosen ID'),
            'komponen_id' => Yii::t('app', 'Komponen ID'),
            'rencana' => Yii::t('app', 'Rencana'),
            'realisasi' => Yii::t('app', 'Realisasi'),
            'sks' => Yii::t('app', 'Sks'),
            'sks_mk' => Yii::t('app', 'Sks Mk'),
            'kode_mk' => Yii::t('app', 'Kode Mk'),
            'nama_mk' => Yii::t('app', 'Nama Mk'),
            'sks_pak' => Yii::t('app', 'Sks Pak'),
            'kondisi' => Yii::t('app', 'Kondisi'),
            'skp_item_id' => Yii::t('app', 'Skp Item ID'),
            'deskripsi' => Yii::t('app', 'Deskripsi'),
            'status_bkd' => Yii::t('app', 'Status Bkd'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[SkpItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkpItem()
    {
        return $this->hasOne(SkpItem::className(), ['id' => 'skp_item_id']);
    }

    /**
     * Gets query for [[Komponen]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKomponen()
    {
        return $this->hasOne(KomponenKegiatan::className(), ['id' => 'komponen_id']);
    }
}
