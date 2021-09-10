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
            [['penguji_ke'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['id', 'id_aktivitas', 'id_uji'], 'string', 'max' => 50],
            [['judul', 'nama_kategori_kegiatan', 'id_dosen'], 'string', 'max' => 255],
            [['id_kategori_kegiatan'], 'string', 'max' => 10],
            [['NIY'], 'string', 'max' => 15],
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
            'id_kategori_kegiatan' => 'Id Kategori Kegiatan',
            'nama_kategori_kegiatan' => 'Nama Kategori Kegiatan',
            'id_dosen' => 'Id Dosen',
            'NIY' => 'Niy',
            'penguji_ke' => 'Penguji Ke',
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
