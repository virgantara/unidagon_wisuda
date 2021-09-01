<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pt".
 *
 * @property string $id
 * @property string|null $nama
 *
 * @property VisitingScientist[] $visitingScientists
 */
class Pt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'string', 'max' => 100],
            [['nama'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
        ];
    }

    /**
     * Gets query for [[VisitingScientists]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitingScientists()
    {
        return $this->hasMany(VisitingScientist::className(), ['id_universitas' => 'id']);
    }
}
