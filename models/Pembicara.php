<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pembicara".
 *
 * @property int $id
 * @property string|null $id_pembicara
 * @property int|null $id_kategori_pembicara
 * @property int|null $id_kategori_capaian_luaran
 * @property string|null $id_kategori_kegiatan
 * @property string|null $nama_kategori_kegiatan
 * @property string|null $nama_kategori_pencapaian
 * @property string|null $judul_makalah
 * @property string|null $nama_pertemuan_ilmiah
 * @property string|null $penyelenggara_kegiatan
 * @property string|null $tanggal_pelaksanaan
 * @property string|null $sister_id
 * @property string|null $no_sk_tugas
 * @property string|null $tanggal_sk_penugasan
 * @property string|null $bahasa
 * @property string|null $updated_at
 * @property string|null $created_at
 * @property string|null $NIY
 *
 * @property User $nIY
 * @property KategoriKegiatan $kategoriKegiatan
 * @property CapaianLuaran $kategoriCapaianLuaran
 * @property PembicaraFiles[] $pembicaraFiles
 */
class Pembicara extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pembicara';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kategori_pembicara', 'id_kategori_capaian_luaran'], 'integer'],
            [['tanggal_pelaksanaan', 'tanggal_sk_penugasan', 'updated_at', 'created_at'], 'safe'],
            [['id_pembicara', 'nama_kategori_pencapaian', 'sister_id', 'no_sk_tugas', 'bahasa'], 'string', 'max' => 100],
            [['id_kategori_kegiatan', 'NIY'], 'string', 'max' => 15],
            [['nama_kategori_kegiatan', 'judul_makalah', 'nama_pertemuan_ilmiah', 'penyelenggara_kegiatan'], 'string', 'max' => 255],
            [['NIY'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['NIY' => 'NIY']],
            [['id_kategori_kegiatan'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriKegiatan::className(), 'targetAttribute' => ['id_kategori_kegiatan' => 'id']],
            [['id_kategori_capaian_luaran'], 'exist', 'skipOnError' => true, 'targetClass' => CapaianLuaran::className(), 'targetAttribute' => ['id_kategori_capaian_luaran' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_pembicara' => 'Id Pembicara',
            'id_kategori_pembicara' => 'Kategori Pembicara',
            'id_kategori_capaian_luaran' => 'Kategori Capaian Luaran',
            'id_kategori_kegiatan' => 'Kategori Kegiatan',
            'nama_kategori_kegiatan' => 'Nama Kategori Kegiatan',
            'nama_kategori_pencapaian' => 'Nama Kategori Pencapaian',
            'judul_makalah' => 'Judul Makalah',
            'nama_pertemuan_ilmiah' => 'Nama Pertemuan Ilmiah',
            'penyelenggara_kegiatan' => 'Penyelenggara Kegiatan',
            'tanggal_pelaksanaan' => 'Tanggal Pelaksanaan',
            'sister_id' => 'Sister ID',
            'no_sk_tugas' => 'No Sk Tugas',
            'tanggal_sk_penugasan' => 'Tanggal Sk Penugasan',
            'bahasa' => 'Bahasa',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'NIY' => 'Niy',
        ];
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

    /**
     * Gets query for [[KategoriKegiatan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKategoriKegiatan()
    {
        return $this->hasOne(KategoriKegiatan::className(), ['id' => 'id_kategori_kegiatan']);
    }

    /**
     * Gets query for [[KategoriCapaianLuaran]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKategoriCapaianLuaran()
    {
        return $this->hasOne(CapaianLuaran::className(), ['id' => 'id_kategori_capaian_luaran']);
    }

    /**
     * Gets query for [[PembicaraFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPembicaraFiles()
    {
        return $this->hasMany(PembicaraFiles::className(), ['pembicara_id' => 'id']);
    }
}
