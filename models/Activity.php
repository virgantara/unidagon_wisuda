<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "activity".
 *
 * @property int $id
 * @property string $type
 * @property string|null $message
 * @property int|null $created_by
 * @property int $created_at
 * @property string $action
 * @property string|null $metadata
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'created_at', 'action'], 'required'],
            [['message', 'metadata'], 'string'],
            [['created_by', 'created_at'], 'integer'],
            [['type', 'action'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'message' => Yii::t('app', 'Message'),
            'created_by' => Yii::t('app', 'Created By'),
            'created_at' => Yii::t('app', 'Created At'),
            'action' => Yii::t('app', 'Action'),
            'metadata' => Yii::t('app', 'Metadata'),
        ];
    }
}
