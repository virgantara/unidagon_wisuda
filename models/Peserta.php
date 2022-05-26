<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "peserta".
 *
 * @property int $id
 * @property string $nim
 * @property string $nama_lengkap
 * @property string $fakultas
 * @property string $prodi
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string $status_warga
 * @property string $warga_negara
 * @property string $alamat
 * @property string $no_telp
 * @property string $nama_ayah
 * @property string $pekerjaan_ayah
 * @property string $nama_ibu
 * @property string $pekerjaan_ibu
 * @property string|null $pas_photo
 * @property string|null $ijazah
 * @property string|null $akta_kelahiran
 * @property string|null $kwitansi_jilid
 * @property string|null $surat_bebas_pinjaman
 * @property string|null $resume_skripsi
 * @property string|null $surat_bebas_tunggakan
 * @property string|null $transkrip
 * @property string|null $skl_tahfidz
 * @property string|null $kwitansi_wisuda
 * @property string|null $tanda_keluar_asrama
 * @property string|null $surat_jalan
 * @property string|null $skripsi
 * @property string|null $abstrak
 * @property string $kode_pendaftaran
 * @property string $kampus
 * @property string|null $status_validasi
 * @property string $kmi
 * @property string|null $bukti_revisi_bahasa
 * @property string|null $bukti_layouter
 * @property string|null $bukti_perpus
 * @property string|null $created
 * @property string|null $updated_at
 * @property int $periode_id
 * @property string|null $drive_path
 *
 * @property Periode $periode
 */
class Peserta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'peserta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nim', 'nama_lengkap', 'fakultas', 'prodi', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'status_warga', 'warga_negara', 'alamat', 'no_telp','periode_id'], 'required','on'=>'sce_form1'],
            [['nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu'], 'required','on'=>'sce_form2'],
            [['nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu'], 'required','on'=>'sce_form3'],
            [['nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu'], 'required','on'=>'sce_form4'],
            [['alamat', 'drive_path'], 'string'],
            [['created', 'updated_at'], 'safe'],
            [['periode_id'], 'integer'],
            [['nim', 'kampus'], 'string', 'max' => 50],
            [['nama_lengkap', 'pas_photo', 'ijazah', 'akta_kelahiran', 'kwitansi_jilid', 'surat_bebas_pinjaman', 'resume_skripsi', 'surat_bebas_tunggakan', 'transkrip', 'skl_tahfidz', 'kwitansi_wisuda', 'tanda_keluar_asrama', 'surat_jalan', 'skripsi', 'abstrak', 'bukti_revisi_bahasa', 'bukti_layouter', 'bukti_perpus'], 'string', 'max' => 255],
            [['fakultas', 'prodi', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'status_warga', 'warga_negara', 'no_telp', 'nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu'], 'string', 'max' => 100],
            [['kode_pendaftaran', 'status_validasi', 'kmi'], 'string', 'max' => 20],
            [['periode_id'], 'exist', 'skipOnError' => true, 'targetClass' => Periode::className(), 'targetAttribute' => ['periode_id' => 'id_periode']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nim' => Yii::t('app', 'NIM'),
            'nama_lengkap' => Yii::t('app', 'Nama Lengkap'),
            'fakultas' => Yii::t('app', 'Fakultas'),
            'prodi' => Yii::t('app', 'Prodi'),
            'tempat_lahir' => Yii::t('app', 'Tempat Lahir'),
            'tanggal_lahir' => Yii::t('app', 'Tanggal Lahir'),
            'jenis_kelamin' => Yii::t('app', 'Jenis Kelamin'),
            'status_warga' => Yii::t('app', 'Status Warga'),
            'warga_negara' => Yii::t('app', 'Warga Negara'),
            'alamat' => Yii::t('app', 'Alamat'),
            'no_telp' => Yii::t('app', 'No Telp'),
            'nama_ayah' => Yii::t('app', 'Nama Ayah'),
            'pekerjaan_ayah' => Yii::t('app', 'Pekerjaan Ayah'),
            'nama_ibu' => Yii::t('app', 'Nama Ibu'),
            'pekerjaan_ibu' => Yii::t('app', 'Pekerjaan Ibu'),
            'pas_photo' => Yii::t('app', 'Pas Photo'),
            'ijazah' => Yii::t('app', 'Ijazah'),
            'akta_kelahiran' => Yii::t('app', 'Akta Kelahiran'),
            'kwitansi_jilid' => Yii::t('app', 'Kwitansi Jilid'),
            'surat_bebas_pinjaman' => Yii::t('app', 'Surat Bebas Pinjaman'),
            'resume_skripsi' => Yii::t('app', 'Resume Skripsi'),
            'surat_bebas_tunggakan' => Yii::t('app', 'Surat Bebas Tunggakan'),
            'transkrip' => Yii::t('app', 'Transkrip'),
            'skl_tahfidz' => Yii::t('app', 'Skl Tahfidz'),
            'kwitansi_wisuda' => Yii::t('app', 'Kwitansi Wisuda'),
            'tanda_keluar_asrama' => Yii::t('app', 'Tanda Keluar Asrama'),
            'surat_jalan' => Yii::t('app', 'Surat Jalan'),
            'skripsi' => Yii::t('app', 'Skripsi'),
            'abstrak' => Yii::t('app', 'Abstrak'),
            'kode_pendaftaran' => Yii::t('app', 'Kode Pendaftaran'),
            'kampus' => Yii::t('app', 'Kampus'),
            'status_validasi' => Yii::t('app', 'Status Validasi'),
            'kmi' => Yii::t('app', 'Kmi'),
            'bukti_revisi_bahasa' => Yii::t('app', 'Bukti Revisi Bahasa'),
            'bukti_layouter' => Yii::t('app', 'Bukti Layouter'),
            'bukti_perpus' => Yii::t('app', 'Bukti Perpus'),
            'created' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'periode_id' => Yii::t('app', 'Periode ID'),
            'drive_path' => Yii::t('app', 'Drive Path'),
        ];
    }

    /**
     * Gets query for [[Periode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriode()
    {
        return $this->hasOne(Periode::className(), ['id_periode' => 'periode_id']);
    }
}
