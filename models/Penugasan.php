<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penugasan".
 *
 * @property int $id
 * @property string|null $sister_id
 * @property string|null $NIY
 * @property string $status_pegawai
 * @property string $nama_ikatan_kerja
 * @property string $nama_jenjang_pendidikan
 * @property string $unit_kerja
 * @property string $perguruan_tinggi
 * @property string|null $terhitung_mulai_tanggal_surat_tugas
 * @property string|null $tanggal_selesai
 * @property string|null $no_sk_tugas
 * @property string|null $tgl_sk_tugas
 * @property string|null $id_jenis_keluar
 * @property int|null $id_status_kepegawaian
 * @property string|null $id_ikatan_kerja
 * @property string|null $id_perguruan_tinggi
 * @property string|null $id_unit_kerja
 * @property string|null $updated_at
 * @property string|null $created_at
 */
class Penugasan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penugasan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status_pegawai', 'nama_ikatan_kerja', 'nama_jenjang_pendidikan', 'unit_kerja', 'perguruan_tinggi'], 'required'],
            [['terhitung_mulai_tanggal_surat_tugas', 'tanggal_selesai', 'tgl_sk_tugas', 'updated_at', 'created_at'], 'safe'],
            [['id_status_kepegawaian'], 'integer'],
            [['sister_id', 'status_pegawai', 'nama_ikatan_kerja', 'nama_jenjang_pendidikan', 'unit_kerja', 'perguruan_tinggi'], 'string', 'max' => 100],
            [['NIY'], 'string', 'max' => 15],
            [['no_sk_tugas', 'id_jenis_keluar', 'id_ikatan_kerja', 'id_perguruan_tinggi', 'id_unit_kerja'], 'string', 'max' => 50],
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
            'NIY' => 'Niy',
            'status_pegawai' => 'Status Pegawai',
            'nama_ikatan_kerja' => 'Nama Ikatan Kerja',
            'nama_jenjang_pendidikan' => 'Nama Jenjang Pendidikan',
            'unit_kerja' => 'Unit Kerja',
            'perguruan_tinggi' => 'Perguruan Tinggi',
            'terhitung_mulai_tanggal_surat_tugas' => 'Terhitung Mulai Tanggal Surat Tugas',
            'tanggal_selesai' => 'Tanggal Selesai',
            'no_sk_tugas' => 'No Sk Tugas',
            'tgl_sk_tugas' => 'Tgl Sk Tugas',
            'id_jenis_keluar' => 'Id Jenis Keluar',
            'id_status_kepegawaian' => 'Id Status Kepegawaian',
            'id_ikatan_kerja' => 'Id Ikatan Kerja',
            'id_perguruan_tinggi' => 'Id Perguruan Tinggi',
            'id_unit_kerja' => 'Id Unit Kerja',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }
}
