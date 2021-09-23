<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "skp".
 *
 * @property string $id
 * @property string|null $pejabat_penilai
 * @property string|null $pegawai_dinilai
 * @property int|null $jabatan_penilai_id
 * @property int|null $jabatan_pegawai_id
 * @property int|null $periode_id
 * @property string|null $status_skp 1=diajukan,2=disetujui,3=ditolak
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property User $pegawaiDinilai
 * @property User $pejabatPenilai
 * @property BkdPeriode $periode
 * @property Jabatan $jabatanPegawai
 * @property Jabatan $jabatanPenilai
 * @property SkpItem[] $skpItems
 */
class Skp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['jabatan_penilai_id', 'jabatan_pegawai_id', 'periode_id'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['id'], 'string', 'max' => 50],
            [['pejabat_penilai', 'pegawai_dinilai'], 'string', 'max' => 15],
            [['status_skp'], 'string', 'max' => 1],
            [['id'], 'unique'],
            [['pegawai_dinilai'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['pegawai_dinilai' => 'NIY']],
            [['pejabat_penilai'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['pejabat_penilai' => 'NIY']],
            [['periode_id'], 'exist', 'skipOnError' => true, 'targetClass' => BkdPeriode::className(), 'targetAttribute' => ['periode_id' => 'tahun_id']],
            [['jabatan_pegawai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jabatan::className(), 'targetAttribute' => ['jabatan_pegawai_id' => 'ID']],
            [['jabatan_penilai_id'], 'exist', 'skipOnError' => true, 'targetClass' => Jabatan::className(), 'targetAttribute' => ['jabatan_penilai_id' => 'ID']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pejabat_penilai' => 'Pejabat Penilai',
            'pegawai_dinilai' => 'Pegawai Dinilai',
            'jabatan_penilai_id' => 'Jabatan Penilai',
            'jabatan_pegawai_id' => 'Jabatan Pegawai',
            'periode_id' => 'Periode ID',
            'status_skp' => 'Status SKP',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[PegawaiDinilai]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiDinilai()
    {
        return $this->hasOne(User::className(), ['NIY' => 'pegawai_dinilai']);
    }

    /**
     * Gets query for [[PejabatPenilai]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPejabatPenilai()
    {
        return $this->hasOne(User::className(), ['NIY' => 'pejabat_penilai']);
    }

    /**
     * Gets query for [[Periode]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriode()
    {
        return $this->hasOne(BkdPeriode::className(), ['tahun_id' => 'periode_id']);
    }

    /**
     * Gets query for [[JabatanPegawai]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJabatanPegawai()
    {
        return $this->hasOne(Jabatan::className(), ['ID' => 'jabatan_pegawai_id']);
    }

    /**
     * Gets query for [[JabatanPenilai]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJabatanPenilai()
    {
        return $this->hasOne(Jabatan::className(), ['ID' => 'jabatan_penilai_id']);
    }

    /**
     * Gets query for [[SkpItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkpItems()
    {
        return $this->hasMany(SkpItem::className(), ['skp_id' => 'id']);
    }
}
