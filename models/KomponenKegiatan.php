<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "komponen_kegiatan".
 *
 * @property int $id
 * @property string|null $kode
 * @property int $unsur_id
 * @property string $nama
 * @property string|null $subunsur
 * @property string|null $kondisi
 * @property float $angka_kredit
 * @property float|null $angka_kredit_pak
 * @property string|null $satuan
 *
 * @property UnsurUtama $unsur
 * @property Organisasi[] $organisasis
 * @property Pembicara[] $pembicaras
 * @property Pengabdian[] $pengabdians
 * @property PengelolaJurnal[] $pengelolaJurnals
 * @property Penghargaan[] $penghargaans
 * @property PenunjangLain[] $penunjangLains
 * @property Publikasi[] $publikasis
 * @property SkpItem[] $skpItems
 */
class KomponenKegiatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'komponen_kegiatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['unsur_id', 'nama', 'angka_kredit'], 'required'],
            [['unsur_id'], 'integer'],
            [['angka_kredit', 'angka_kredit_pak'], 'number'],
            [['kode'], 'string', 'max' => 20],
            [['nama', 'subunsur'], 'string', 'max' => 255],
            [['kondisi', 'satuan'], 'string', 'max' => 100],
            [['kode'], 'unique'],
            [['unsur_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnsurUtama::className(), 'targetAttribute' => ['unsur_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'unsur_id' => 'Unsur',
            'nama' => 'Nama',
            'subunsur' => 'Subunsur',
            'kondisi' => 'Kondisi',
            'angka_kredit' => 'Angka Kredit BKD',
            'angka_kredit_pak' => 'Angka Kredit PAK',
            'satuan' => 'Satuan',
        ];
    }

    /**
     * Gets query for [[Unsur]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnsur()
    {
        return $this->hasOne(UnsurUtama::className(), ['id' => 'unsur_id']);
    }

    /**
     * Gets query for [[Organisasis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganisasis()
    {
        return $this->hasMany(Organisasi::className(), ['komponen_kegiatan_id' => 'id']);
    }

    /**
     * Gets query for [[Pembicaras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPembicaras()
    {
        return $this->hasMany(Pembicara::className(), ['komponen_kegiatan_id' => 'id']);
    }

    /**
     * Gets query for [[Pengabdians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengabdians()
    {
        return $this->hasMany(Pengabdian::className(), ['komponen_kegiatan_id' => 'id']);
    }

    /**
     * Gets query for [[PengelolaJurnals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengelolaJurnals()
    {
        return $this->hasMany(PengelolaJurnal::className(), ['komponen_kegiatan_id' => 'id']);
    }

    /**
     * Gets query for [[Penghargaans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenghargaans()
    {
        return $this->hasMany(Penghargaan::className(), ['komponen_kegiatan_id' => 'id']);
    }

    /**
     * Gets query for [[PenunjangLains]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenunjangLains()
    {
        return $this->hasMany(PenunjangLain::className(), ['komponen_kegiatan_id' => 'id']);
    }

    /**
     * Gets query for [[Publikasis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPublikasis()
    {
        return $this->hasMany(Publikasi::className(), ['kegiatan_id' => 'id']);
    }

    /**
     * Gets query for [[SkpItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkpItems()
    {
        return $this->hasMany(SkpItem::className(), ['komponen_kegiatan_id' => 'id']);
    }
}
