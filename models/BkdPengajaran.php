<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bkd_pengajaran".
 *
 * @property int $id
 * @property int|null $pengajaran_id
 * @property float|null $nilai_bkd
 * @property string|null $NIY
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property Pengajaran $pengajaran
 * @property User $nIY
 */
class BkdPengajaran extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bkd_pengajaran';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pengajaran_id'], 'integer'],
            [['nilai_bkd'], 'number'],
            [['updated_at', 'created_at'], 'safe'],
            [['NIY'], 'string', 'max' => 15],
            [['pengajaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pengajaran::className(), 'targetAttribute' => ['pengajaran_id' => 'ID']],
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
            'pengajaran_id' => 'Pengajaran ID',
            'nilai_bkd' => 'Nilai Bkd',
            'NIY' => 'Niy',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Pengajaran]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengajaran()
    {
        return $this->hasOne(Pengajaran::className(), ['ID' => 'pengajaran_id']);
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
