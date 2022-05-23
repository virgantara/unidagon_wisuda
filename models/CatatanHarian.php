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
 * @property string|null $tautan
 * @property float|null $sks_bkd
 * @property float|null $sks_mk
 * @property string|null $kode_mk
 * @property string|null $nama_mk
 * @property string|null $jadwal_id
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
            [['poin', 'sks_bkd', 'sks_mk'], 'number'],
            [['skp_item_id', 'kode_mk', 'jadwal_id'], 'string', 'max' => 50],
            [['is_selesai'], 'string', 'max' => 1],
            [['kondisi', 'nama_mk'], 'string', 'max' => 100],
            [['tautan'], 'string', 'max' => 255],
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
            'id' => Yii::t('app', 'ID'),
            'skp_item_id' => Yii::t('app', 'Item Kegiatan SKP'),
            'user_id' => Yii::t('app', 'User ID'),
            'deskripsi' => Yii::t('app', 'Deskripsi'),
            'tanggal' => Yii::t('app', 'Tanggal'),
            'is_selesai' => Yii::t('app', 'Is Selesai'),
            'poin' => Yii::t('app', 'Poin'),
            'approved_by' => Yii::t('app', 'Approved By'),
            'kondisi' => Yii::t('app', 'Kondisi'),
            'tautan' => Yii::t('app', 'Tautan'),
            'sks_bkd' => Yii::t('app', 'Sks Bkd'),
            'sks_mk' => Yii::t('app', 'Sks Mk'),
            'kode_mk' => Yii::t('app', 'Kode Mk'),
            'nama_mk' => Yii::t('app', 'Nama Mk'),
            'jadwal_id' => Yii::t('app', 'Jadwal ID'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_at' => Yii::t('app', 'Created At'),
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
