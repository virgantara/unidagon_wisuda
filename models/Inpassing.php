<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inpassing".
 *
 * @property int $id
 * @property string|null $sister_id
 * @property string $nama_golongan
 * @property string $nomor_sk_inpassing
 * @property string|null $tanggal_sk
 * @property string|null $sk_inpassing_terhitung_mulai_tanggal
 * @property string|null $NIY
 * @property string|null $id_sdm
 * @property int|null $id_pangkat_golongan
 * @property string|null $pangkat
 * @property string|null $golongan
 * @property float|null $angka_kredit
 * @property int|null $masa_kerja_tahun
 * @property int|null $masa_kerja_bulan
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property User $nIY
 */
class Inpassing extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inpassing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_golongan', 'nomor_sk_inpassing'], 'required'],
            [['tanggal_sk', 'sk_inpassing_terhitung_mulai_tanggal', 'updated_at', 'created_at'], 'safe'],
            [['id_pangkat_golongan', 'masa_kerja_tahun', 'masa_kerja_bulan'], 'integer'],
            [['angka_kredit'], 'number'],
            [['sister_id', 'nama_golongan', 'nomor_sk_inpassing'], 'string', 'max' => 100],
            [['NIY'], 'string', 'max' => 15],
            [['id_sdm', 'pangkat', 'golongan'], 'string', 'max' => 50],
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
            'sister_id' => 'Sister ID',
            'nama_golongan' => 'Nama Golongan',
            'nomor_sk_inpassing' => 'Nomor Sk Inpassing',
            'tanggal_sk' => 'Tanggal Sk',
            'sk_inpassing_terhitung_mulai_tanggal' => 'Sk Inpassing Terhitung Mulai Tanggal',
            'NIY' => 'Niy',
            'id_sdm' => 'Id Sdm',
            'id_pangkat_golongan' => 'Id Pangkat Golongan',
            'pangkat' => 'Pangkat',
            'golongan' => 'Golongan',
            'angka_kredit' => 'Angka Kredit',
            'masa_kerja_tahun' => 'Masa Kerja Tahun',
            'masa_kerja_bulan' => 'Masa Kerja Bulan',
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
}
