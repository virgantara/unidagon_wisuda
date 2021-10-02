<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sertifikasi".
 *
 * @property string $id
 * @property string|null $jenis_sertifikasi
 * @property string|null $bidang_studi
 * @property int|null $tahun_sertifikasi
 * @property string|null $sk_sertifikasi
 * @property string|null $nomor_registrasi
 * @property string|null $NIY
 * @property int|null $id_jenis_sertifikasi
 * @property string|null $id_bidang_studi
 * @property string|null $sister_id
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property User $nIY
 * @property BidangIlmu $bidangStudi
 */
class Sertifikasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sertifikasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['tahun_sertifikasi', 'id_jenis_sertifikasi'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['id', 'jenis_sertifikasi', 'bidang_studi', 'sk_sertifikasi', 'nomor_registrasi', 'sister_id'], 'string', 'max' => 50],
            [['NIY'], 'string', 'max' => 15],
            [['id_bidang_studi'], 'string', 'max' => 5],
            [['id'], 'unique'],
            [['NIY'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['NIY' => 'NIY']],
            [['id_bidang_studi'], 'exist', 'skipOnError' => true, 'targetClass' => BidangIlmu::className(), 'targetAttribute' => ['id_bidang_studi' => 'kode']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis_sertifikasi' => 'Jenis Sertifikasi',
            'bidang_studi' => 'Bidang Studi',
            'tahun_sertifikasi' => 'Tahun Sertifikasi',
            'sk_sertifikasi' => 'Sk Sertifikasi',
            'nomor_registrasi' => 'Nomor Registrasi',
            'NIY' => 'Niy',
            'id_jenis_sertifikasi' => 'Id Jenis Sertifikasi',
            'id_bidang_studi' => 'Bidang Studi',
            'sister_id' => 'Sister ID',
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
     * Gets query for [[BidangStudi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBidangStudi()
    {
        return $this->hasOne(BidangIlmu::className(), ['kode' => 'id_bidang_studi']);
    }
}
