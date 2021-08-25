<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "luaran_lain".
 *
 * @property int $id
 * @property int $jenis_luaran_id
 * @property string $judul
 * @property string $deskripsi
 * @property int $tahun_pelaksanaan
 * @property string|null $tanggal_pelaksanaan
 * @property string|null $berkas
 * @property string|null $shared_link
 * @property string|null $sumber_dana
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string $ver
 * @property string|null $komentar
 * @property string|null $uuid
 * @property string|null $parent_id
 * @property string|null $jenis_litab
 * @property string|null $NIY
 *
 * @property JenisLuaran $jenisLuaran
 * @property User $nIY
 * @property LuaranLainAuthor[] $luaranLainAuthors
 */
class LuaranLain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'luaran_lain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenis_luaran_id', 'judul', 'deskripsi', 'tahun_pelaksanaan', 'ver'], 'required'],
            [['jenis_luaran_id', 'tahun_pelaksanaan'], 'integer'],
            [['judul', 'berkas', 'komentar'], 'string'],
            [['tanggal_pelaksanaan', 'created_at', 'updated_at'], 'safe'],
            [['deskripsi', 'shared_link', 'sumber_dana', 'ver'], 'string', 'max' => 255],
            [['uuid', 'parent_id'], 'string', 'max' => 50],
            [['jenis_litab'], 'string', 'max' => 1],
            [['NIY'], 'string', 'max' => 15],
            [['jenis_luaran_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisLuaran::className(), 'targetAttribute' => ['jenis_luaran_id' => 'id']],
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
            'jenis_luaran_id' => 'Jenis Luaran ID',
            'judul' => 'Judul',
            'deskripsi' => 'Deskripsi',
            'tahun_pelaksanaan' => 'Tahun Pelaksanaan',
            'tanggal_pelaksanaan' => 'Tanggal Pelaksanaan',
            'berkas' => 'Berkas',
            'shared_link' => 'Shared Link',
            'sumber_dana' => 'Sumber Dana',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'ver' => 'Ver',
            'komentar' => 'Komentar',
            'uuid' => 'Uuid',
            'parent_id' => 'Parent ID',
            'jenis_litab' => 'Jenis Litab',
            'NIY' => 'Niy',
        ];
    }

    /**
     * Gets query for [[JenisLuaran]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisLuaran()
    {
        return $this->hasOne(JenisLuaran::className(), ['id' => 'jenis_luaran_id']);
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

    /**
     * Gets query for [[LuaranLainAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLuaranLainAuthors()
    {
        return $this->hasMany(LuaranLainAuthor::className(), ['luaran_lain_id' => 'id']);
    }
}
