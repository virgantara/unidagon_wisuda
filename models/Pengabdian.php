<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pengabdian".
 *
 * @property int $ID
 * @property string $NIY
 * @property string $judul_penelitian_pengabdian
 * @property string|null $skema
 * @property string|null $jenis_penelitian_pengabdian
 * @property string|null $jenis_kegiatan insidental, non
 * @property string|null $tgl_mulai
 * @property string|null $tgl_akhir
 * @property string|null $tingkat
 * @property int $tahun_kegiatan
 * @property int $tahun_usulan
 * @property int $tahun_dilaksanakan
 * @property string $tempat_kegiatan
 * @property string|null $no_sk_tugas
 * @property string|null $tgl_sk_tugas
 * @property int|null $durasi_kegiatan
 * @property int $tahun_pelaksanaan_ke
 * @property string|null $nama_tahun_ajaran
 * @property string|null $nama_skim
 * @property float|null $nilai
 * @property string|null $sister_id
 * @property string|null $kategori_kegiatan_id
 * @property string|null $skim_kegiatan_id
 * @property string|null $kelompok_bidang_id
 * @property float $dana_dikti
 * @property float $dana_pt
 * @property float $dana_institusi_lain
 * @property int|null $jumlah_mahasiswa
 * @property int|null $jumlah_alumni
 * @property int|null $jumlah_staf
 * @property int|null $sdm_iptek_id
 * @property int|null $komponen_kegiatan_id
 * @property string|null $is_claimed
 * @property string|null $jenis_sumber_dana mandiri, dalam, luar
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property User $nIY
 * @property KategoriKegiatan $kategoriKegiatan
 * @property KelompokBidang $kelompokBidang
 * @property SkimKegiatan $skimKegiatan
 * @property Tingkat $tingkat0
 * @property JenisKegiatan $jenisKegiatan
 * @property PengabdianAnggota[] $pengabdianAnggotas
 * @property PengabdianJurnal[] $pengabdianJurnals
 * @property PengabdianMediaMassa[] $pengabdianMediaMassas
 * @property PengabdianMitra[] $pengabdianMitras
 */
class Pengabdian extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengabdian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NIY', 'judul_penelitian_pengabdian', 'tahun_kegiatan', 'tahun_usulan', 'tahun_dilaksanakan', 'tempat_kegiatan', 'tahun_pelaksanaan_ke', 'dana_dikti', 'dana_pt', 'dana_institusi_lain'], 'required'],
            [['tgl_mulai', 'tgl_akhir', 'tgl_sk_tugas', 'updated_at', 'created_at'], 'safe'],
            [['kategori_kegiatan_id','komponen_kegiatan_id'],'required','on'=>'update'],
            [['tahun_kegiatan', 'tahun_usulan', 'tahun_dilaksanakan', 'durasi_kegiatan', 'tahun_pelaksanaan_ke', 'jumlah_mahasiswa', 'jumlah_alumni', 'jumlah_staf', 'sdm_iptek_id', 'komponen_kegiatan_id'], 'integer'],
            [['nilai', 'dana_dikti', 'dana_pt', 'dana_institusi_lain'], 'number'],
            [['NIY'], 'string', 'max' => 15],
            [['judul_penelitian_pengabdian'], 'string', 'max' => 500],
            [['skema'], 'string', 'max' => 20],
            [['jenis_penelitian_pengabdian', 'kategori_kegiatan_id', 'jenis_sumber_dana'], 'string', 'max' => 10],
            [['jenis_kegiatan', 'tingkat', 'is_claimed'], 'string', 'max' => 1],
            [['tempat_kegiatan'], 'string', 'max' => 255],
            [['no_sk_tugas', 'nama_tahun_ajaran', 'nama_skim', 'sister_id', 'skim_kegiatan_id', 'kelompok_bidang_id'], 'string', 'max' => 100],
            [['NIY'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['NIY' => 'NIY']],
            [['kategori_kegiatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriKegiatan::className(), 'targetAttribute' => ['kategori_kegiatan_id' => 'id']],
            [['kelompok_bidang_id'], 'exist', 'skipOnError' => true, 'targetClass' => KelompokBidang::className(), 'targetAttribute' => ['kelompok_bidang_id' => 'id']],
            [['skim_kegiatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => SkimKegiatan::className(), 'targetAttribute' => ['skim_kegiatan_id' => 'id']],
            [['tingkat'], 'exist', 'skipOnError' => true, 'targetClass' => Tingkat::className(), 'targetAttribute' => ['tingkat' => 'id']],
            [['jenis_kegiatan'], 'exist', 'skipOnError' => true, 'targetClass' => JenisKegiatan::className(), 'targetAttribute' => ['jenis_kegiatan' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'NIY' => 'NIY',
            'judul_penelitian_pengabdian' => 'Judul Penelitian Pengabdian',
            'skema' => 'Skema',
            'jenis_penelitian_pengabdian' => 'Jenis Penelitian Pengabdian',
            'jenis_kegiatan' => 'Jenis Kegiatan',
            'tgl_mulai' => 'Tgl Mulai',
            'tgl_akhir' => 'Tgl Akhir',
            'tingkat' => 'Tingkat',
            'tahun_kegiatan' => 'Tahun Kegiatan',
            'tahun_usulan' => 'Tahun Usulan',
            'tahun_dilaksanakan' => 'Tahun Dilaksanakan',
            'tempat_kegiatan' => 'Tempat Kegiatan',
            'no_sk_tugas' => 'No SK Tugas',
            'tgl_sk_tugas' => 'Tgl SK Tugas',
            'durasi_kegiatan' => 'Durasi Kegiatan',
            'tahun_pelaksanaan_ke' => 'Tahun Pelaksanaan Ke',
            'nama_tahun_ajaran' => 'Nama Tahun Ajaran',
            'nama_skim' => 'Nama Skim',
            'nilai' => 'Nilai',
            'sister_id' => 'Sister ID',
            'kategori_kegiatan_id' => 'Kategori Kegiatan ID',
            'skim_kegiatan_id' => 'Skim Kegiatan ID',
            'kelompok_bidang_id' => 'Kelompok Bidang ID',
            'dana_dikti' => 'Dana DIKTI',
            'dana_pt' => 'Dana PT',
            'dana_institusi_lain' => 'Dana Institusi Lain',
            'jumlah_mahasiswa' => 'Jumlah Mahasiswa',
            'jumlah_alumni' => 'Jumlah Alumni',
            'jumlah_staf' => 'Jumlah Staf',
            'sdm_iptek_id' => 'Sdm Iptek ID',
            'komponen_kegiatan_id' => 'Komponen Kegiatan ID',
            'is_claimed' => 'Is Claimed',
            'jenis_sumber_dana' => 'Jenis Sumber Dana',
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

    /**
     * Gets query for [[KelompokBidang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKelompokBidang()
    {
        return $this->hasOne(KelompokBidang::className(), ['id' => 'kelompok_bidang_id']);
    }

    /**
     * Gets query for [[SkimKegiatan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkimKegiatan()
    {
        return $this->hasOne(SkimKegiatan::className(), ['id' => 'skim_kegiatan_id']);
    }

    /**
     * Gets query for [[Tingkat0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTingkat0()
    {
        return $this->hasOne(Tingkat::className(), ['id' => 'tingkat']);
    }

    /**
     * Gets query for [[JenisKegiatan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisKegiatan()
    {
        return $this->hasOne(JenisKegiatan::className(), ['id' => 'jenis_kegiatan']);
    }

    /**
     * Gets query for [[PengabdianAnggotas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengabdianAnggotas()
    {
        return $this->hasMany(PengabdianAnggota::className(), ['pengabdian_id' => 'ID']);
    }

    /**
     * Gets query for [[PengabdianJurnals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengabdianJurnals()
    {
        return $this->hasMany(PengabdianJurnal::className(), ['pengabdian_id' => 'ID']);
    }

    /**
     * Gets query for [[PengabdianMediaMassas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengabdianMediaMassas()
    {
        return $this->hasMany(PengabdianMediaMassa::className(), ['pengabdian_id' => 'ID']);
    }

    /**
     * Gets query for [[PengabdianMitras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengabdianMitras()
    {
        return $this->hasMany(PengabdianMitra::className(), ['pengabdian_id' => 'ID']);
    }
}
