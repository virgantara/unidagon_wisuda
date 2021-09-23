<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "m_jabatan".
 *
 * @property int $id
 * @property string $nama
 * @property string|null $access_role
 * @property int|null $parent_id
 *
 * @property Jabatan[] $jabatans
 * @property AuthItem $accessRole
 * @property MJabatan $parent
 * @property MJabatan[] $mJabatans
 * @property UnitKerja[] $unitKerjas
 */
class MJabatan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_jabatan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama'], 'required'],
            [['parent_id'], 'integer'],
            [['nama'], 'string', 'max' => 255],
            [['access_role'], 'string', 'max' => 64],
            [['access_role'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['access_role' => 'name']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => MJabatan::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'access_role' => 'Access Role',
            'parent_id' => 'Parent ID',
        ];
    }

    /**
     * Gets query for [[Jabatans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJabatans()
    {
        return $this->hasMany(Jabatan::className(), ['jabatan_id' => 'id']);
    }

    /**
     * Gets query for [[AccessRole]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccessRole()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'access_role']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(MJabatan::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[MJabatans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMJabatans()
    {
        return $this->hasMany(MJabatan::className(), ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[UnitKerjas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnitKerjas()
    {
        return $this->hasMany(UnitKerja::className(), ['jabatan_id' => 'id']);
    }
}
