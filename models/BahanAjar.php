<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bahan_ajar".
 *
 * @property string $id
 * @property string|null $sister_id
 * @property int|null $id_kategori_capaian_luaran
 * @property int|null $id_penelitian_pengabdian
 * @property int|null $id_jenis_bahan_ajar
 * @property string|null $judul
 * @property string|null $nama_penerbit
 * @property string|null $isbn
 * @property string|null $tanggal_terbit
 * @property string|null $sk_penugasan
 * @property string|null $tanggal_sk_penugasan
 * @property string|null $nama_jenis
 * @property string|null $id_kategori_kegiatan
 * @property string|null $NIY
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property User $nIY
 * @property KategoriKegiatan $kategoriKegiatan
 * @property JenisBahanAjar $jenisBahanAjar
 * @property CapaianLuaran $kategoriCapaianLuaran
 * @property Penulis[] $penulis
 */
class BahanAjar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bahan_ajar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id_kategori_capaian_luaran', 'id_penelitian_pengabdian', 'id_jenis_bahan_ajar'], 'integer'],
            [['tanggal_terbit', 'tanggal_sk_penugasan', 'updated_at', 'created_at'], 'safe'],
            [['id', 'sister_id'], 'string', 'max' => 50],
            [['judul', 'nama_penerbit'], 'string', 'max' => 255],
            [['isbn', 'sk_penugasan', 'nama_jenis'], 'string', 'max' => 100],
            [['id_kategori_kegiatan', 'NIY'], 'string', 'max' => 15],
            [['id'], 'unique'],
            [['NIY'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['NIY' => 'NIY']],
            [['id_kategori_kegiatan'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriKegiatan::className(), 'targetAttribute' => ['id_kategori_kegiatan' => 'id']],
            [['id_jenis_bahan_ajar'], 'exist', 'skipOnError' => true, 'targetClass' => JenisBahanAjar::className(), 'targetAttribute' => ['id_jenis_bahan_ajar' => 'id']],
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
            'sister_id' => 'Sister ID',
            'id_kategori_capaian_luaran' => 'Kategori Capaian Luaran',
            'id_penelitian_pengabdian' => 'Penelitian Pengabdian',
            'id_jenis_bahan_ajar' => 'Jenis Bahan Ajar',
            'judul' => 'Judul',
            'nama_penerbit' => 'Nama Penerbit',
            'isbn' => 'Isbn',
            'tanggal_terbit' => 'Tanggal Terbit',
            'sk_penugasan' => 'Sk Penugasan',
            'tanggal_sk_penugasan' => 'Tanggal Sk Penugasan',
            'nama_jenis' => 'Nama Jenis',
            'id_kategori_kegiatan' => 'Kategori Kegiatan',
            'NIY' => 'Niy',
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
        return $this->hasOne(KategoriKegiatan::className(), ['id' => 'id_kategori_kegiatan']);
    }

    /**
     * Gets query for [[JenisBahanAjar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisBahanAjar()
    {
        return $this->hasOne(JenisBahanAjar::className(), ['id' => 'id_jenis_bahan_ajar']);
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
     * Gets query for [[Penulis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenulis()
    {
        return $this->hasMany(Penulis::className(), ['bahan_ajar_id' => 'id']);
    }
}
