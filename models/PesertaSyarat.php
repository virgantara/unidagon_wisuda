<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "peserta_syarat".
 *
 * @property int $id
 * @property int|null $peserta_id
 * @property int $syarat_id
 * @property string $file_path
 *
 * @property Peserta $peserta
 * @property Syarat $syarat
 */
class PesertaSyarat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'peserta_syarat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['peserta_id', 'syarat_id'], 'integer'],
            [['syarat_id', 'file_path'], 'required'],
            [['file_path'], 'string', 'max' => 500],
            [['peserta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Peserta::className(), 'targetAttribute' => ['peserta_id' => 'id']],
            [['syarat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Syarat::className(), 'targetAttribute' => ['syarat_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'peserta_id' => Yii::t('app', 'Peserta ID'),
            'syarat_id' => Yii::t('app', 'Syarat ID'),
            'file_path' => Yii::t('app', 'File Path'),
        ];
    }

    /**
     * Gets query for [[Peserta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeserta()
    {
        return $this->hasOne(Peserta::className(), ['id' => 'peserta_id']);
    }

    /**
     * Gets query for [[Syarat]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSyarat()
    {
        return $this->hasOne(Syarat::className(), ['id' => 'syarat_id']);
    }
}
