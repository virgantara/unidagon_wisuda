<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organisasi".
 *
 * @property int $ID
 * @property string $NIY
 * @property int $tahun_awal
 * @property string $tahun_akhir
 * @property string $organisasi
 * @property string|null $instansi_profesi
 * @property string $jabatan
 * @property string|null $f_sk
 * @property string|null $tanggal_mulai_keanggotaan
 * @property string|null $selesai_keanggotaan
 * @property string|null $sister_id
 * @property string|null $kategori_kegiatan_id
 * @property int|null $komponen_kegiatan_id
 * @property float|null $sks_bkd
 * @property string|null $is_claimed
 * @property string $update_at
 * @property string $ver
 *
 * @property User $nIY
 * @property KategoriKegiatan $kategoriKegiatan
 * @property KomponenKegiatan $komponenKegiatan
 */
class Organisasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organisasi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NIY', 'tahun_awal', 'tahun_akhir', 'organisasi', 'jabatan'], 'required'],
            [['tahun_awal', 'komponen_kegiatan_id'], 'integer'],
            [['tanggal_mulai_keanggotaan', 'selesai_keanggotaan', 'update_at'], 'safe'],
            [['sks_bkd'], 'number'],
            [['ver'], 'string'],
            [['NIY'], 'string', 'max' => 15],
            [['tahun_akhir', 'kategori_kegiatan_id'], 'string', 'max' => 10],
            [['organisasi', 'f_sk'], 'string', 'max' => 200],
            [['instansi_profesi', 'sister_id'], 'string', 'max' => 100],
            [['jabatan'], 'string', 'max' => 50],
            [['is_claimed'], 'string', 'max' => 1],
            [['NIY'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['NIY' => 'NIY']],
            [['kategori_kegiatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => KategoriKegiatan::className(), 'targetAttribute' => ['kategori_kegiatan_id' => 'id']],
            [['komponen_kegiatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => KomponenKegiatan::className(), 'targetAttribute' => ['komponen_kegiatan_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'NIY' => 'Niy',
            'tahun_awal' => 'Tahun Awal',
            'tahun_akhir' => 'Tahun Akhir',
            'organisasi' => 'Organisasi',
            'instansi_profesi' => 'Instansi Profesi',
            'jabatan' => 'Jabatan',
            'f_sk' => 'F Sk',
            'tanggal_mulai_keanggotaan' => 'Tanggal Mulai Keanggotaan',
            'selesai_keanggotaan' => 'Selesai Keanggotaan',
            'sister_id' => 'Sister ID',
            'kategori_kegiatan_id' => 'Kategori Kegiatan ID',
            'komponen_kegiatan_id' => 'Komponen Kegiatan ID',
            'sks_bkd' => 'Sks Bkd',
            'is_claimed' => 'Is Claimed',
            'update_at' => 'Update At',
            'ver' => 'Ver',
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
     * Gets query for [[KategoriKegiatan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKategoriKegiatan()
    {
        return $this->hasOne(KategoriKegiatan::className(), ['id' => 'kategori_kegiatan_id']);
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
}
