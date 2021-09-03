<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log_sync".
 *
 * @property string $id
 * @property string $keterangan
 * @property string|null $NIY
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property User $nIY
 */
class LogSync extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_sync';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'keterangan'], 'required'],
            [['keterangan'], 'string'],
            [['updated_at', 'created_at'], 'safe'],
            [['id'], 'string', 'max' => 50],
            [['NIY'], 'string', 'max' => 15],
            [['id'], 'unique'],
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
            'keterangan' => 'Keterangan',
            'NIY' => 'Niy',
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
