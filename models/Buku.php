<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "buku".
 *
 * @property int $ID
 * @property string|null $NIY
 * @property int|null $tahun
 * @property string $judul
 * @property string|null $penerbit
 * @property string|null $f_karya
 * @property string|null $ISBN
 * @property string|null $vol
 * @property string|null $link
 * @property string|null $ver
 * @property string|null $komentar
 * @property int|null $jenis_luaran_id
 * @property string|null $jenis_litab
 * @property string|null $parent_id
 * @property string|null $uuid
 * @property int|null $halaman
 * @property string|null $tanggal_terbit
 * @property string|null $no_sk_tugas
 * @property string|null $tanggal_sk_penugasan
 * @property string|null $sister_id
 * @property string|null $id_kategori_capaian_luaran
 * @property string|null $id_jenis_bahan_ajar
 * @property string|null $nama_kategori_kegiatan
 * @property string|null $kategori_kegiatan_id
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property JenisLuaran $jenisLuaran
 * @property KategoriKegiatan $kategoriKegiatan
 * @property User $nIY
 * @property BukuAuthor[] $bukuAuthors
 */
class Buku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'buku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun', 'jenis_luaran_id', 'halaman'], 'integer'],
            [['judul'], 'required'],
            [['ver', 'komentar'], 'string'],
            [['tanggal_terbit', 'tanggal_sk_penugasan', 'updated_at', 'created_at'], 'safe'],
            [['NIY'], 'string', 'max' => 15],
            [['judul'], 'string', 'max' => 500],
            [['penerbit', 'nama_kategori_kegiatan'], 'string', 'max' => 255],
            [['f_karya', 'id_kategori_capaian_luaran'], 'string', 'max' => 200],
            [['ISBN', 'vol', 'parent_id', 'uuid', 'no_sk_tugas'], 'string', 'max' => 50],
            [['link'], 'string', 'max' => 250],
            [['jenis_litab'], 'string', 'max' => 1],
            [['sister_id', 'id_jenis_bahan_ajar'], 'string', 'max' => 100],
            [['kategori_kegiatan_id'], 'string', 'max' => 10],
            [['jenis_luaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisLuaran::className(), 'targetAttribute' => ['jenis_luaran_id' => 'id']],
            [['kategori_kegiatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriKegiatan::className(), 'targetAttribute' => ['kategori_kegiatan_id' => 'id']],
            [['NIY'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['NIY' => 'NIY']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'NIY' => 'Niy',
            'tahun' => 'Tahun',
            'judul' => 'Judul',
            'penerbit' => 'Penerbit',
            'f_karya' => 'F Karya',
            'ISBN' => 'Isbn',
            'vol' => 'Vol',
            'link' => 'Link',
            'ver' => 'Ver',
            'komentar' => 'Komentar',
            'jenis_luaran_id' => 'Jenis Luaran ID',
            'jenis_litab' => 'Jenis Litab',
            'parent_id' => 'Parent ID',
            'uuid' => 'Uuid',
            'halaman' => 'Halaman',
            'tanggal_terbit' => 'Tanggal Terbit',
            'no_sk_tugas' => 'No Sk Tugas',
            'tanggal_sk_penugasan' => 'Tanggal Sk Penugasan',
            'sister_id' => 'Sister ID',
            'id_kategori_capaian_luaran' => 'Id Kategori Capaian Luaran',
            'id_jenis_bahan_ajar' => 'Id Jenis Bahan Ajar',
            'nama_kategori_kegiatan' => 'Nama Kategori Kegiatan',
            'kategori_kegiatan_id' => 'Kategori Kegiatan ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[JenisLuaran]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisLuaran()
    {
        return $this->hasOne(JenisLuaran::className(), ['id' => 'jenis_luaran_id']);
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
     * Gets query for [[NIY]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNIY()
    {
        return $this->hasOne(User::className(), ['NIY' => 'NIY']);
    }

    /**
     * Gets query for [[BukuAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBukuAuthors()
    {
        return $this->hasMany(BukuAuthor::className(), ['buku_id' => 'ID']);
    }
}
