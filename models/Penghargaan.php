<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penghargaan".
 *
 * @property int $ID
 * @property string $NIY
 * @property int $tahun
 * @property string $bentuk
 * @property string $pemberi
 * @property string|null $f_penghargaan
 * @property string $ver
 * @property string|null $sister_id
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property User $nIY
 */
class Penghargaan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penghargaan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NIY', 'tahun', 'bentuk', 'pemberi'], 'required'],
            [['tahun'], 'integer'],
            [['ver'], 'string'],
            [['updated_at', 'created_at'], 'safe'],
            [['NIY'], 'string', 'max' => 15],
            [['bentuk', 'sister_id'], 'string', 'max' => 100],
            [['pemberi'], 'string', 'max' => 50],
            [['f_penghargaan'], 'string', 'max' => 200],
            [['NIY'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['NIY' => 'NIY']],
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
            'tahun' => 'Tahun',
            'bentuk' => 'Bentuk',
            'pemberi' => 'Pemberi',
            'f_penghargaan' => 'F Penghargaan',
            'ver' => 'Ver',
            'sister_id' => 'Sister ID',
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
