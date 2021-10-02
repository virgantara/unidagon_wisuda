<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orasi_ilmiah".
 *
 * @property int $id
 * @property string|null $NIY
 * @property string|null $nama_kategori_kegiatan
 * @property string|null $nama_kategori_pencapaian
 * @property string|null $kategori_kegiatan_id
 * @property string|null $judul_buku_makalah
 * @property string|null $nama_pertemuan_ilmiah
 * @property string|null $penyelenggara_kegiatan
 * @property string|null $tanggal_pelaksanaan
 * @property string|null $no_sk_tugas
 * @property string|null $tanggal_sk_penugasan
 * @property string|null $bahasa
 * @property string|null $sister_id
 * @property int|null $id_kategori_capaian_luaran
 * @property int|null $id_kategori_pembicara
 * @property string|null $tingkat_pertemuan_id
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property User $nIY
 * @property KategoriKegiatan $kategoriKegiatan
 * @property Tingkat $tingkatPertemuan
 * @property KategoriPembicara $kategoriPembicara
 */
class OrasiIlmiah extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orasi_ilmiah';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tanggal_pelaksanaan', 'tanggal_sk_penugasan', 'updated_at', 'created_at'], 'safe'],
            [['id_kategori_capaian_luaran', 'id_kategori_pembicara'], 'integer'],
            [['NIY'], 'string', 'max' => 15],
            [['nama_kategori_kegiatan', 'nama_kategori_pencapaian', 'judul_buku_makalah', 'nama_pertemuan_ilmiah', 'penyelenggara_kegiatan'], 'string', 'max' => 255],
            [['kategori_kegiatan_id'], 'string', 'max' => 10],
            [['no_sk_tugas', 'bahasa', 'sister_id'], 'string', 'max' => 100],
            [['tingkat_pertemuan_id'], 'string', 'max' => 1],
            [['file_path'], 'file', 'extensions' => 'pdf','maxSize' => 1024 * 1024 * 2],
            [['NIY'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['NIY' => 'NIY']],
            [['kategori_kegiatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriKegiatan::className(), 'targetAttribute' => ['kategori_kegiatan_id' => 'id']],
            [['tingkat_pertemuan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tingkat::className(), 'targetAttribute' => ['tingkat_pertemuan_id' => 'id']],
            [['id_kategori_pembicara'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriPembicara::className(), 'targetAttribute' => ['id_kategori_pembicara' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'NIY' => 'Niy',
            'nama_kategori_kegiatan' => 'Nama Kategori Kegiatan',
            'nama_kategori_pencapaian' => 'Nama Kategori Pencapaian',
            'kategori_kegiatan_id' => 'Kategori Kegiatan',
            'judul_buku_makalah' => 'Judul Buku Makalah',
            'nama_pertemuan_ilmiah' => 'Nama Pertemuan Ilmiah',
            'penyelenggara_kegiatan' => 'Penyelenggara Kegiatan',
            'tanggal_pelaksanaan' => 'Tanggal Pelaksanaan',
            'no_sk_tugas' => 'No SK Tugas',
            'tanggal_sk_penugasan' => 'Tanggal SK Penugasan',
            'bahasa' => 'Bahasa',
            'sister_id' => 'Sister ID',
            'id_kategori_capaian_luaran' => 'Kategori Capaian Luaran',
            'id_kategori_pembicara' => 'Kategori Pembicara',
            'tingkat_pertemuan_id' => 'Tingkat Pertemuan',
            'updated_at' => 'Updated At',
            'file_path' => 'Bukti Kegiatan',
            'created_at' => 'Created At',
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
        return $this->hasOne(KategoriKegiatan::className(), ['id' => 'kategori_kegiatan_id']);
    }

    /**
     * Gets query for [[TingkatPertemuan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTingkatPertemuan()
    {
        return $this->hasOne(Tingkat::className(), ['id' => 'tingkat_pertemuan_id']);
    }

    /**
     * Gets query for [[KategoriPembicara]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKategoriPembicara()
    {
        return $this->hasOne(KategoriPembicara::className(), ['id' => 'id_kategori_pembicara']);
    }
}
