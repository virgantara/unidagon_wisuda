<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "buku".
 *
 * @property int $ID
 * @property string $NIY
 * @property int $tahun
 * @property string $judul
 * @property string $penerbit
 * @property string|null $f_karya
 * @property string $ISBN
 * @property string $vol
 * @property string $link
 * @property string $ver
 * @property string|null $komentar
 * @property int|null $jenis_luaran_id
 * @property string|null $jenis_litab
 * @property string|null $parent_id
 * @property string|null $uuid
 * @property int|null $halaman
 * @property string|null $tanggal_terbit
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property JenisLuaran $jenisLuaran
 * @property User $nIY
 * @property BukuAuthor[] $bukuAuthors
 */
class Buku extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'buku';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NIY', 'tahun', 'judul', 'penerbit'], 'required'],
            [['tahun', 'jenis_luaran_id', 'halaman'], 'integer'],
            [['ver', 'komentar'], 'string'],
            [['tanggal_terbit', 'updated_at', 'created_at'], 'safe'],
            [['NIY'], 'string', 'max' => 15],
            [['judul'], 'string', 'max' => 500],
            [['penerbit'], 'string', 'max' => 255],
            [['f_karya'], 'string', 'max' => 255],
            [['f_karya'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf','maxSize' => 1024 * 1024 * 3],
            [['ISBN', 'vol', 'parent_id', 'uuid'], 'string', 'max' => 50],
            [['link'], 'string', 'max' => 250],
            [['jenis_litab'], 'string', 'max' => 1],
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
            'ID' => 'ID',
            'NIY' => 'NIY',
            'tahun' => 'Tahun',
            'judul' => 'Judul',
            'penerbit' => 'Penerbit',
            'f_karya' => 'F Karya',
            'ISBN' => 'ISBN',
            'vol' => 'Vol',
            'link' => 'Link',
            'ver' => 'Ver',
            'komentar' => 'Komentar',
            'jenis_luaran_id' => 'Jenis Luaran ID',
            'jenis_litab' => 'Jenis Litab',
            'parent_id' => 'Parent ID',
            'uuid' => 'Uuid',
            'halaman' => 'Halaman',
            'tanggal_terbit' => 'Tanggal Terbit',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
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
     * Gets query for [[BukuAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBukuAuthors()
    {
        return $this->hasMany(BukuAuthor::className(), ['buku_id' => 'ID']);
    }
}
