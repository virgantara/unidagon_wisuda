<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bimbingan_mahasiswa_dosen".
 *
 * @property string $id
 * @property string|null $NIY
 * @property string|null $id_sdm
 * @property string|null $nama
 * @property string|null $kategori_kegiatan
 * @property int|null $urutan
 * @property string|null $bimbingan_mahasiswa_id
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property BimbinganMahasiswa $bimbinganMahasiswa
 * @property User $nIY
 */
class BimbinganMahasiswaDosen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bimbingan_mahasiswa_dosen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['urutan'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['id', 'id_sdm', 'bimbingan_mahasiswa_id'], 'string', 'max' => 50],
            [['NIY'], 'string', 'max' => 15],
            [['nama', 'kategori_kegiatan'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['bimbingan_mahasiswa_id'], 'exist', 'skipOnError' => true, 'targetClass' => BimbinganMahasiswa::className(), 'targetAttribute' => ['bimbingan_mahasiswa_id' => 'id']],
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
            'NIY' => 'Niy',
            'id_sdm' => 'Id Sdm',
            'nama' => 'Nama',
            'kategori_kegiatan' => 'Kategori Kegiatan',
            'urutan' => 'Urutan',
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
