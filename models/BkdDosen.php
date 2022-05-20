<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bkd_dosen".
 *
 * @property int $id
 * @property int $tahun_id
 * @property int $dosen_id
 * @property int|null $komponen_id
 * @property float|null $sks
 * @property float|null $sks_pak
 * @property string $kondisi
 * @property string|null $deskripsi
 * @property string|null $skp_item_id
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property SkpItem $skpItem
 * @property KomponenKegiatan $komponen
 */
class BkdDosen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bkd_dosen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tahun_id', 'dosen_id', 'kondisi'], 'required'],
            [['tahun_id', 'dosen_id', 'komponen_id'], 'integer'],
            [['sks', 'sks_pak'], 'number'],
            [['deskripsi'], 'string'],
            [['updated_at', 'created_at','status_bkd','realisasi','rencana'], 'safe'],
            [['kondisi'], 'string', 'max' => 100],
            [['skp_item_id'], 'string', 'max' => 50],
            [['tahun_id', 'dosen_id', 'komponen_id', 'kondisi'], 'unique', 'targetAttribute' => ['tahun_id', 'dosen_id', 'komponen_id', 'kondisi']],
            [['skp_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => SkpItem::className(), 'targetAttribute' => ['skp_item_id' => 'id']],
            [['komponen_id'], 'exist', 'skipOnError' => true, 'targetClass' => KomponenKegiatan::className(), 'targetAttribute' => ['komponen_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tahun_id' => 'Tahun ID',
            'dosen_id' => 'Dosen ID',
            'komponen_id' => 'Komponen ID',
            'sks' => 'Sks',
            'sks_pak' => 'Sks Pak',
            'kondisi' => 'Kondisi',
            'deskripsi' => 'Deskripsi',
            'skp_item_id' => 'Skp Item ID',
            'status_bkd' => Yii::t('app','Status'),
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[SkpItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkpItem()
    {
        return $this->hasOne(SkpItem::className(), ['id' => 'skp_item_id']);
    }

    /**
     * Gets query for [[Komponen]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKomponen()
    {
        return $this->hasOne(KomponenKegiatan::className(), ['id' => 'komponen_id']);
    }
}
