<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bimbingan_mahasiswa_mahasiswa".
 *
 * @property string $id
 * @property string|null $nomor_induk
 * @property string|null $nama
 * @property string|null $peran
 * @property string|null $bimbingan_mahasiswa_id
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property BimbinganMahasiswa $bimbinganMahasiswa
 */
class BimbinganMahasiswaMahasiswa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bimbingan_mahasiswa_mahasiswa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['updated_at', 'created_at'], 'safe'],
            [['id', 'bimbingan_mahasiswa_id'], 'string', 'max' => 50],
            [['nomor_induk'], 'string', 'max' => 20],
            [['nama'], 'string', 'max' => 255],
            [['peran'], 'string', 'max' => 100],
            [['id'], 'unique'],
            [['bimbingan_mahasiswa_id'], 'exist', 'skipOnError' => true, 'targetClass' => BimbinganMahasiswa::className(), 'targetAttribute' => ['bimbingan_mahasiswa_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomor_induk' => 'Nomor Induk',
            'nama' => 'Nama',
            'peran' => 'Peran',
            'bimbingan_mahasiswa_id' => 'Bimbingan Mahasiswa ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[BimbinganMahasiswa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBimbinganMahasiswa()
    {
        return $this->hasOne(BimbinganMahasiswa::className(), ['id' => 'bimbingan_mahasiswa_id']);
    }
}
