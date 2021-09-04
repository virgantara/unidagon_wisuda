<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tes".
 *
 * @property string $id
 * @property string $jenis_tes
 * @property string $nama
 * @property string $penyelenggara
 * @property int $tahun
 * @property float|null $skor
 * @property string|null $NIY
 * @property int|null $id_jenis_tes
 * @property string|null $tanggal
 * @property string|null $sister_id
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property User $nIY
 */
class Tes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'jenis_tes', 'nama', 'penyelenggara', 'tahun'], 'required'],
            [['tahun', 'id_jenis_tes'], 'integer'],
            [['skor'], 'number'],
            [['tanggal', 'updated_at', 'created_at'], 'safe'],
            [['id', 'jenis_tes', 'nama', 'sister_id'], 'string', 'max' => 50],
            [['penyelenggara'], 'string', 'max' => 100],
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
            'jenis_tes' => 'Jenis Tes',
            'nama' => 'Nama',
            'penyelenggara' => 'Penyelenggara',
            'tahun' => 'Tahun',
            'skor' => 'Skor',
            'NIY' => 'Niy',
            'id_jenis_tes' => 'Id Jenis Tes',
            'tanggal' => 'Tanggal',
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
