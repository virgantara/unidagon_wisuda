<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bimbingan_mahasiswa".
 *
 * @property string $id
 * @property string|null $judul
 * @property string|null $jenis_bimbingan
 * @property string|null $program_studi
 * @property string|null $semester
 * @property string|null $lokasi
 * @property string|null $sk_penugasan
 * @property string|null $tanggal_sk_penugasan
 * @property string|null $keterangan
 * @property int|null $komunal
 * @property string|null $sister_id
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property BimbinganMahasiswaDosen[] $bimbinganMahasiswaDosens
 * @property BimbinganMahasiswaMahasiswa[] $bimbinganMahasiswaMahasiswas
 */
class BimbinganMahasiswa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bimbingan_mahasiswa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['tanggal_sk_penugasan', 'updated_at', 'created_at'], 'safe'],
            [['komunal'], 'integer'],
            [['id', 'sister_id'], 'string', 'max' => 50],
            [['judul', 'jenis_bimbingan', 'program_studi', 'lokasi', 'keterangan'], 'string', 'max' => 255],
            [['semester'], 'string', 'max' => 100],
            [['sk_penugasan'], 'string', 'max' => 40],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'judul' => 'Judul',
            'jenis_bimbingan' => 'Jenis Bimbingan',
            'program_studi' => 'Program Studi',
            'semester' => 'Semester',
            'lokasi' => 'Lokasi',
            'sk_penugasan' => 'Sk Penugasan',
            'tanggal_sk_penugasan' => 'Tanggal Sk Penugasan',
            'keterangan' => 'Keterangan',
            'komunal' => 'Komunal',
            'sister_id' => 'Sister ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[BimbinganMahasiswaDosens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBimbinganMahasiswaDosens()
    {
        return $this->hasMany(BimbinganMahasiswaDosen::className(), ['bimbingan_mahasiswa_id' => 'id']);
    }

    /**
     * Gets query for [[BimbinganMahasiswaMahasiswas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBimbinganMahasiswaMahasiswas()
    {
        return $this->hasMany(BimbinganMahasiswaMahasiswa::className(), ['bimbingan_mahasiswa_id' => 'id']);
    }
}
