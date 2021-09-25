<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tendik".
 *
 * @property int $id
 * @property string $NIY
 * @property string|null $nama
 * @property string|null $gender
 * @property string|null $tempat_lahir
 * @property string|null $tanggal_lahir
 * @property string|null $status_kawin
 * @property string|null $agama
 * @property string $jenjang_kode
 * @property string|null $perguruan_tinggi
 * @property string|null $alamat_kampus
 * @property string|null $telp_kampus
 * @property string|null $fax_kampus
 * @property string|null $alamat_rumah
 * @property string|null $telp_hp
 * @property int $unit_id
 * @property int $jabatan_id
 * @property string $jenis_tendik_id
 * @property string|null $nitk
 * @property string|null $sister_id
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property MJenjangPendidikan $jenjangKode
 * @property JenisTendik $jenisTendik
 * @property MJabatanTendik $jabatan
 * @property UnitKerja $unit
 * @property User $nIY
 */
class Tendik extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tendik';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NIY', 'unit_id', 'jabatan_id', 'jenis_tendik_id'], 'required'],
            [['tanggal_lahir', 'updated_at', 'created_at'], 'safe'],
            [['alamat_kampus', 'alamat_rumah'], 'string'],
            [['unit_id', 'jabatan_id'], 'integer'],
            [['NIY', 'telp_kampus', 'fax_kampus', 'telp_hp'], 'string', 'max' => 15],
            [['nama', 'perguruan_tinggi', 'nitk', 'sister_id'], 'string', 'max' => 50],
            [['gender', 'status_kawin'], 'string', 'max' => 100],
            [['tempat_lahir'], 'string', 'max' => 30],
            [['agama'], 'string', 'max' => 20],
            [['jenjang_kode'], 'string', 'max' => 5],
            [['jenis_tendik_id'], 'string', 'max' => 3],
            [['jenjang_kode'], 'exist', 'skipOnError' => true, 'targetClass' => MJenjangPendidikan::className(), 'targetAttribute' => ['jenjang_kode' => 'kode']],
            [['jenis_tendik_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisTendik::className(), 'targetAttribute' => ['jenis_tendik_id' => 'kode']],
            [['jabatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MJabatanTendik::className(), 'targetAttribute' => ['jabatan_id' => 'id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnitKerja::className(), 'targetAttribute' => ['unit_id' => 'id']],
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
            'nama' => 'Nama',
            'gender' => 'Gender',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'status_kawin' => 'Status Kawin',
            'agama' => 'Agama',
            'jenjang_kode' => 'Jenjang Kode',
            'perguruan_tinggi' => 'Perguruan Tinggi',
            'alamat_kampus' => 'Alamat Kampus',
            'telp_kampus' => 'Telp Kampus',
            'fax_kampus' => 'Fax Kampus',
            'alamat_rumah' => 'Alamat Rumah',
            'telp_hp' => 'Telp Hp',
            'unit_id' => 'Unit ID',
            'jabatan_id' => 'Jabatan ID',
            'jenis_tendik_id' => 'Jenis Tendik ID',
            'nitk' => 'Nitk',
            'sister_id' => 'Sister ID',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[JenjangKode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenjangKode()
    {
        return $this->hasOne(MJenjangPendidikan::className(), ['kode' => 'jenjang_kode']);
    }

    /**
     * Gets query for [[JenisTendik]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisTendik()
    {
        return $this->hasOne(JenisTendik::className(), ['kode' => 'jenis_tendik_id']);
    }

    /**
     * Gets query for [[Jabatan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJabatan()
    {
        return $this->hasOne(MJabatanTendik::className(), ['id' => 'jabatan_id']);
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(UnitKerja::className(), ['id' => 'unit_id']);
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
