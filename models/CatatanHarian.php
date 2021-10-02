<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "catatan_harian".
 *
 * @property int $id
 * @property string|null $skp_item_id
 * @property int $user_id
 * @property string $deskripsi
 * @property string|null $tanggal
 * @property string|null $is_selesai 1=setuju,2=tolak
 * @property float|null $poin
 * @property int|null $approved_by
 * @property string|null $kondisi
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property User $user
 * @property SkpItem $skpItem
 */
class CatatanHarian extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'catatan_harian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'deskripsi'], 'required'],
            [['user_id', 'approved_by'], 'integer'],
            [['deskripsi'], 'string'],
            [['tanggal', 'updated_at', 'created_at'], 'safe'],
            [['poin'], 'number'],
            [['skp_item_id'], 'string', 'max' => 50],
            [['is_selesai'], 'string', 'max' => 1],
            [['kondisi'], 'string', 'max' => 100],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'ID']],
            [['skp_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => SkpItem::className(), 'targetAttribute' => ['skp_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'skp_item_id' => 'Item SKP',
            'user_id' => 'User ID',
            'deskripsi' => 'Deskripsi',
            'tanggal' => 'Tanggal',
            'is_selesai' => 'Is Selesai',
            'poin' => 'Poin',
            'approved_by' => 'Approved By',
            'kondisi' => 'Kondisi',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['ID' => 'user_id']);
    }

    /**
     * Gets query for [[SkpItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkpItem()
    {
        return $this->hasOne(SkpItem::className(), ['id' => 'skp_item_id']);
    }

    public static function sumPoinCatatanHarian($user_id)
    {
        $query = CatatanHarian::find()->where(['user_id'=>$user_id]);
        // $query->andFilterWhere(['between','tanggal',$sd, $ed]);
        return $query->sum('poin');
    }
}
