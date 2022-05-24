<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "jabatan".
 *
 * @property int $ID
 * @property string $NIY
 * @property int $jabatan_id
 * @property int $unker_id
 * @property int|null $komponen_kegiatan_id
 * @property string|null $institusi
 * @property string|null $tanggal_awal
 * @property string|null $tanggal_akhir
 * @property string|null $no_sk
 * @property string|null $f_penugasan
 * @property string $update_at
 * @property string $ver
 *
 * @property User $nIY
 * @property MJabatan $jabatan
 * @property UnitKerja $unker
 * @property KomponenKegiatan $komponenKegiatan
 * @property Skp[] $skps
 * @property Skp[] $skps0
 * @property Skp[] $skps1
 */
class Jabatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jabatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NIY', 'jabatan_id', 'unker_id'], 'required'],
            [['jabatan_id', 'unker_id', 'komponen_kegiatan_id'], 'integer'],
            [['tanggal_awal', 'tanggal_akhir', 'update_at'], 'safe'],
            [['ver'], 'string'],
            [['NIY'], 'string', 'max' => 15],
            [['institusi'], 'string', 'max' => 50],
            [['no_sk'], 'string', 'max' => 100],
            [['f_penugasan'], 'string', 'max' => 200],
            [['NIY', 'jabatan_id', 'unker_id'], 'unique', 'targetAttribute' => ['NIY', 'jabatan_id', 'unker_id']],
            [['NIY'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['NIY' => 'NIY']],
            [['jabatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MJabatan::className(), 'targetAttribute' => ['jabatan_id' => 'id']],
            [['unker_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnitKerja::className(), 'targetAttribute' => ['unker_id' => 'id']],
            [['komponen_kegiatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => KomponenKegiatan::className(), 'targetAttribute' => ['komponen_kegiatan_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'NIY' => Yii::t('app', 'NIY'),
            'jabatan_id' => Yii::t('app', 'Jabatan'),
            'unker_id' => Yii::t('app', 'Satuan Kerja'),
            'komponen_kegiatan_id' => Yii::t('app', 'Komponen Kegiatan'),
            'institusi' => Yii::t('app', 'Institusi'),
            'tanggal_awal' => Yii::t('app', 'Tanggal SK Awal'),
            'tanggal_akhir' => Yii::t('app', 'Tanggal SK Berakhir'),
            'no_sk' => Yii::t('app', 'No SK'),
            'f_penugasan' => Yii::t('app', 'Penugasan'),
            'update_at' => Yii::t('app', 'Update At'),
            'ver' => Yii::t('app', 'Ver'),
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
     * Gets query for [[Jabatan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJabatan()
    {
        return $this->hasOne(MJabatan::className(), ['id' => 'jabatan_id']);
    }

    /**
     * Gets query for [[Unker]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnker()
    {
        return $this->hasOne(UnitKerja::className(), ['id' => 'unker_id']);
    }

    /**
     * Gets query for [[KomponenKegiatan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKomponenKegiatan()
    {
        return $this->hasOne(KomponenKegiatan::className(), ['id' => 'komponen_kegiatan_id']);
    }

    /**
     * Gets query for [[Skps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkps()
    {
        return $this->hasMany(Skp::className(), ['jabatan_pegawai_id' => 'ID']);
    }

    /**
     * Gets query for [[Skps0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkps0()
    {
        return $this->hasMany(Skp::className(), ['jabatan_penilai_id' => 'ID']);
    }

    /**
     * Gets query for [[Skps1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkps1()
    {
        return $this->hasMany(Skp::className(), ['jabatan_atasan_penilai_id' => 'ID']);
    }

    public function getJabatanDosen(){
        return $this->hasOne(Dosen::className(),['NIY'=>'NIY']);
    }

    public function getJabatanData(){
        return $this->hasOne(DataDiri::className(),['NIY'=>'NIY']);
    }
}
