<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ewmp".
 *
 * @property string $id
 * @property string|null $NIY
 * @property string|null $is_dtps
 * @property float|null $pendidikan_sks_ps
 * @property float|null $pendidikan_sks_ps_lain
 * @property float|null $pendidikan_sks_pt_lain
 * @property float|null $penelitian
 * @property float|null $abdimas
 * @property float|null $penunjang
 * @property float|null $total_sks
 * @property int|null $tahun_akademik
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property User $nIY
 */
class Ewmp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ewmp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['pendidikan_sks_ps', 'pendidikan_sks_ps_lain', 'pendidikan_sks_pt_lain', 'penelitian', 'abdimas', 'penunjang', 'total_sks'], 'number'],
            [['tahun_akademik'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['id'], 'string', 'max' => 50],
            [['NIY'], 'string', 'max' => 15],
            [['is_dtps'], 'string', 'max' => 1],
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
            'NIY' => 'Niy',
            'is_dtps' => 'Is Dtps',
            'pendidikan_sks_ps' => 'Pendidikan Sks Ps',
            'pendidikan_sks_ps_lain' => 'Pendidikan Sks Ps Lain',
            'pendidikan_sks_pt_lain' => 'Pendidikan Sks Pt Lain',
            'penelitian' => 'Penelitian',
            'abdimas' => 'Abdimas',
            'penunjang' => 'Penunjang',
            'total_sks' => 'Total Sks',
            'tahun_akademik' => 'Tahun Akademik',
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
