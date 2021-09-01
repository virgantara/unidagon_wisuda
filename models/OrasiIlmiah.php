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
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property User $nIY
 * @property KategoriKegiatan $kategoriKegiatan
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
            [['NIY'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['NIY' => 'NIY']],
            [['kategori_kegiatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriKegiatan::className(), 'targetAttribute' => ['kategori_kegiatan_id' => 'id']],
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
            'kategori_kegiatan_id' => 'Kategori Kegiatan ID',
            'judul_buku_makalah' => 'Judul Buku Makalah',
            'nama_pertemuan_ilmiah' => 'Nama Pertemuan Ilmiah',
            'penyelenggara_kegiatan' => 'Penyelenggara Kegiatan',
            'tanggal_pelaksanaan' => 'Tanggal Pelaksanaan',
            'no_sk_tugas' => 'No Sk Tugas',
            'tanggal_sk_penugasan' => 'Tanggal Sk Penugasan',
            'bahasa' => 'Bahasa',
            'sister_id' => 'Sister ID',
            'id_kategori_capaian_luaran' => 'Id Kategori Capaian Luaran',
            'id_kategori_pembicara' => 'Id Kategori Pembicara',
            'updated_at' => 'Updated At',
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
}
