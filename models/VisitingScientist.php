<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visiting_scientist".
 *
 * @property int $id
 * @property string|null $perguruan_tinggi_pengundang
 * @property int|null $durasi_kegiatan
 * @property string|null $tanggal_pelaksanaan
 * @property string|null $kategori_kegiatan_id
 * @property string|null $nama_penelitian_pengabdian
 * @property string|null $id_penelitian_pengabdian
 * @property string|null $nama_kategori_pencapaian
 * @property int|null $id_kategori_capaian_luaran
 * @property string|null $id_universitas
 * @property string|null $kegiatan_penting_yang_dilakukan
 * @property string|null $no_sk_tugas
 * @property string|null $tanggal_sk_penugasan
 * @property int|null $durasi
 * @property string|null $NIY
 * @property string|null $sister_id
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property KategoriKegiatan $kategoriKegiatan
 * @property User $nIY
 * @property CapaianLuaran $kategoriCapaianLuaran
 * @property Pt $universitas
 */
class VisitingScientist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visiting_scientist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['durasi_kegiatan', 'id_kategori_capaian_luaran', 'durasi'], 'integer'],
            [['tanggal_pelaksanaan', 'tanggal_sk_penugasan', 'updated_at', 'created_at','tingkat'], 'safe'],
            [['perguruan_tinggi_pengundang', 'nama_penelitian_pengabdian', 'nama_kategori_pencapaian', 'kegiatan_penting_yang_dilakukan'], 'string', 'max' => 255],
            [['kategori_kegiatan_id'], 'string', 'max' => 10],
            [['id_penelitian_pengabdian', 'id_universitas', 'no_sk_tugas', 'sister_id'], 'string', 'max' => 100],
            [['NIY'], 'string', 'max' => 15],
            [['kategori_kegiatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriKegiatan::className(), 'targetAttribute' => ['kategori_kegiatan_id' => 'id']],
            [['NIY'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['NIY' => 'NIY']],
            [['id_kategori_capaian_luaran'], 'exist', 'skipOnError' => true, 'targetClass' => CapaianLuaran::className(), 'targetAttribute' => ['id_kategori_capaian_luaran' => 'id']],
            [['id_universitas'], 'exist', 'skipOnError' => true, 'targetClass' => Pt::className(), 'targetAttribute' => ['id_universitas' => 'id']],
            [['tingkat'], 'exist', 'skipOnError' => true, 'targetClass' => Tingkat::className(), 'targetAttribute' => ['tingkat' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'perguruan_tinggi_pengundang' => 'Perguruan Tinggi Pengundang',
            'durasi_kegiatan' => 'Lama Kegiatan (Hari)',
            'tanggal_pelaksanaan' => 'Tanggal Pelaksanaan',
            'kategori_kegiatan_id' => 'Kategori Kegiatan',
            'nama_penelitian_pengabdian' => 'Nama Penelitian Pengabdian',
            'id_penelitian_pengabdian' => 'Penelitian Pengabdian',
            'nama_kategori_pencapaian' => 'Nama Kategori Pencapaian',
            'id_kategori_capaian_luaran' => 'Kategori Capaian Luaran',
            'id_universitas' => 'Universitas',
            'kegiatan_penting_yang_dilakukan' => 'Kegiatan Penting Yang Dilakukan',
            'no_sk_tugas' => 'No SK Tugas',
            'tanggal_sk_penugasan' => 'Tanggal SK Penugasan',
            'durasi' => 'Durasi',
            'NIY' => 'NIY',
            'sister_id' => 'Sister ID',
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
     * Gets query for [[NIY]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNIY()
    {
        return $this->hasOne(User::className(), ['NIY' => 'NIY']);
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
     * Gets query for [[Universitas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUniversitas()
    {
        return $this->hasOne(Pt::className(), ['id' => 'id_universitas']);
    }

    public function getTingkat0()
    {
        return $this->hasOne(Tingkat::className(), ['id' => 'tingkat']);
    }
}
