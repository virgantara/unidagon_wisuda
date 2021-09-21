<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penunjang_lain".
 *
 * @property int $id
 * @property string|null $kategori_kegiatan_id
 * @property int|null $komponen_kegiatan_id
 * @property int $jenis_panitia_id
 * @property string $tingkat_id
 * @property string|null $nama_kegiatan
 * @property string|null $instansi
 * @property string|null $no_sk_tugas
 * @property string|null $tanggal_mulai
 * @property string|null $tanggal_selesai
 * @property string|null $NIY
 * @property float|null $sks_bkd
 * @property string|null $is_claimed
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property KategoriKegiatan $kategoriKegiatan
 * @property KomponenKegiatan $komponenKegiatan
 * @property Tingkat $tingkat
 * @property JenisPanitia $jenisPanitia
 * @property User $nIY
 */
class PenunjangLain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penunjang_lain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['komponen_kegiatan_id', 'jenis_panitia_id'], 'integer'],
            [['jenis_panitia_id', 'tingkat_id'], 'required'],
            [['komponen_kegiatan_id','kategori_kegiatan_id'], 'required','on' => 'update'],
            [['tanggal_mulai', 'tanggal_selesai', 'updated_at', 'created_at','file_path'], 'safe'],
            [['sks_bkd'], 'number'],
            [['kategori_kegiatan_id'], 'string', 'max' => 100],
            [['tingkat_id', 'is_claimed'], 'string', 'max' => 1],
            [['nama_kegiatan', 'instansi', 'no_sk_tugas'], 'string', 'max' => 255],
            [['NIY'], 'string', 'max' => 15],
            [['file_path'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf','maxSize' => 1024 * 1024],
            [['kategori_kegiatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriKegiatan::className(), 'targetAttribute' => ['kategori_kegiatan_id' => 'id']],
            [['komponen_kegiatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => KomponenKegiatan::className(), 'targetAttribute' => ['komponen_kegiatan_id' => 'id']],
            [['tingkat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tingkat::className(), 'targetAttribute' => ['tingkat_id' => 'id']],
            [['jenis_panitia_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisPanitia::className(), 'targetAttribute' => ['jenis_panitia_id' => 'id']],
            [['NIY'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['NIY' => 'NIY']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kategori_kegiatan_id' => 'Kategori Kegiatan',
            'komponen_kegiatan_id' => 'Komponen Kegiatan',
            'jenis_panitia_id' => 'Jenis Panitia',
            'tingkat_id' => 'Tingkat',
            'nama_kegiatan' => 'Nama Kegiatan',
            'instansi' => 'Instansi',
            'no_sk_tugas' => 'No SK Tugas',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'NIY' => 'Niy',
            'sks_bkd' => 'Sks Bkd',
            'is_claimed' => 'Is Claimed',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[KategoriKegiatan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKategoriKegiatan()
    {
        return $this->hasOne(KategoriKegiatan::className(), ['id' => 'kategori_kegiatan_id']);
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
     * Gets query for [[Tingkat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTingkat()
    {
        return $this->hasOne(Tingkat::className(), ['id' => 'tingkat_id']);
    }

    /**
     * Gets query for [[JenisPanitia]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisPanitia()
    {
        return $this->hasOne(JenisPanitia::className(), ['id' => 'jenis_panitia_id']);
    }

    /**
     * Gets query for [[NIY]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNIY()
    {
        return $this->hasOne(User::className(), ['NIY' => 'NIY']);
    }
}
