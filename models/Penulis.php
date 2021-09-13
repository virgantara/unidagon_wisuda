<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penulis".
 *
 * @property string $id
 * @property string|null $bahan_ajar_id
 * @property string|null $NIY
 * @property string|null $nama
 * @property int|null $urutan
 * @property string|null $afiliasi
 * @property string|null $peran
 * @property string|null $jenis
 * @property string|null $id_sdm
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property BahanAjar $bahanAjar
 * @property User $nIY
 */
class Penulis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penulis';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['urutan'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['id', 'bahan_ajar_id', 'id_sdm'], 'string', 'max' => 50],
            [['NIY'], 'string', 'max' => 15],
            [['nama', 'afiliasi', 'peran', 'jenis'], 'string', 'max' => 100],
            [['id'], 'unique'],
            [['bahan_ajar_id'], 'exist', 'skipOnError' => true, 'targetClass' => BahanAjar::className(), 'targetAttribute' => ['bahan_ajar_id' => 'id']],
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
            'bahan_ajar_id' => 'Bahan Ajar ID',
            'NIY' => 'Niy',
            'nama' => 'Nama',
            'urutan' => 'Urutan',
            'afiliasi' => 'Afiliasi',
            'peran' => 'Peran',
            'jenis' => 'Jenis',
            'id_sdm' => 'Id Sdm',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[BahanAjar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBahanAjar()
    {
        return $this->hasOne(BahanAjar::className(), ['id' => 'bahan_ajar_id']);
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
