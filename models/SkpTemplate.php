<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "skp_template".
 *
 * @property int $id
 * @property int|null $komponen_kegiatan_id
 * @property string $nama
 * @property float $target_qty
 * @property string $target_satuan
 * @property float $target_mutu
 * @property float $target_waktu
 * @property string $target_waktu_satuan
 * @property string|null $peran
 *
 * @property KomponenKegiatan $komponenKegiatan
 * @property AuthItem $peran0
 */
class SkpTemplate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skp_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['komponen_kegiatan_id'], 'integer'],
            [['nama', 'target_satuan', 'target_waktu_satuan'], 'required'],
            [['target_qty', 'target_mutu', 'target_waktu'], 'number'],
            [['nama'], 'string', 'max' => 500],
            [['target_satuan', 'target_waktu_satuan'], 'string', 'max' => 100],
            [['peran'], 'string', 'max' => 64],
            [['komponen_kegiatan_id'], 'exist', 'skipOnError' => true, 'targetClass' => KomponenKegiatan::className(), 'targetAttribute' => ['komponen_kegiatan_id' => 'id']],
            [['peran'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['peran' => 'name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'komponen_kegiatan_id' => 'Komponen Kegiatan ID',
            'nama' => 'Nama',
            'target_qty' => 'Target Qty',
            'target_satuan' => 'Target Satuan',
            'target_mutu' => 'Target Mutu',
            'target_waktu' => 'Target Waktu',
            'target_waktu_satuan' => 'Target Waktu Satuan',
            'peran' => 'Peran',
        ];
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
     * Gets query for [[Peran0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeran0()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'peran']);
    }
}
