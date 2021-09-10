<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "uji_mahasiswa".
 *
 * @property string $id
 * @property string|null $id_aktivitas
 * @property string|null $judul
 * @property string|null $id_uji
 * @property string|null $id_kategori_kegiatan
 * @property string|null $nama_kategori_kegiatan
 * @property string|null $id_dosen
 * @property string|null $NIY
 * @property int|null $penguji_ke
 * @property int|null $id_jenis_aktivitas
 * @property string|null $nama_jenis_aktivitas
 * @property string|null $id_prodi
 * @property int|null $id_semester
 * @property string|null $lokasi
 * @property string|null $sk_tugas
 * @property string|null $tanggal_sk_tugas
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property KategoriKegiatan $kategoriKegiatan
 * @property User $nIY
 */
class UjiMahasiswa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'uji_mahasiswa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['penguji_ke', 'id_jenis_aktivitas', 'id_semester'], 'integer'],
            [['tanggal_sk_tugas', 'updated_at', 'created_at'], 'safe'],
            [['id', 'id_aktivitas', 'id_uji', 'id_prodi'], 'string', 'max' => 50],
            [['judul', 'nama_kategori_kegiatan', 'id_dosen', 'nama_jenis_aktivitas', 'lokasi'], 'string', 'max' => 255],
            [['id_kategori_kegiatan'], 'string', 'max' => 10],
            [['NIY'], 'string', 'max' => 15],
            [['sk_tugas'], 'string', 'max' => 100],
            [['id'], 'unique'],
            [['id_kategori_kegiatan'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriKegiatan::className(), 'targetAttribute' => ['id_kategori_kegiatan' => 'id']],
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
            'id_aktivitas' => 'Id Aktivitas',
            'judul' => 'Judul',
            'id_uji' => 'Id Uji',
            'id_kategori_kegiatan' => 'Kategori Kegiatan',
            'nama_kategori_kegiatan' => 'Nama Kategori Kegiatan',
            'id_dosen' => 'Dosen',
            'NIY' => 'Niy',
            'penguji_ke' => 'Penguji Ke',
            'id_jenis_aktivitas' => 'Id Jenis Aktivitas',
            'nama_jenis_aktivitas' => 'Jenis Aktivitas',
            'id_prodi' => 'Prodi',
            'id_semester' => 'Semester',
            'lokasi' => 'Lokasi',
            'sk_tugas' => 'SK Tugas',
            'tanggal_sk_tugas' => 'Tanggal SK Tugas',
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
        return $this->hasOne(KategoriKegiatan::className(), ['id' => 'id_kategori_kegiatan']);
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
