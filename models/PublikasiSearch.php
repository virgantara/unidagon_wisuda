<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Publikasi;

/**
 * PublikasiSearch represents the model behind the search form of `app\models\Publikasi`.
 */
class PublikasiSearch extends Publikasi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'kegiatan_id'], 'integer'],
            [['judul_publikasi_paten', 'nama_jenis_publikasi','nama_kategori_kegiatan', 'tanggal_terbit', 'sister_id', 'updated_at', 'created_at','jumlah_sitasi','kategori_kegiatan_id','jenis_publikasi_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Publikasi::find();
        $query->alias('t');
        // $query->joinWith(['publikasiAuthors as pa']);
        
        

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'kegiatan_id' => $this->kegiatan_id,
            'kategori_kegiatan_id' => $this->kategori_kegiatan_id,
            'jenis_publikasi_id' => $this->jenis_publikasi_id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'jumlah_sitasi' => $this->jumlah_sitasi,
        ]);

        $query->andFilterWhere(['like', 'judul_publikasi_paten', $this->judul_publikasi_paten])
            ->andFilterWhere(['like', 'nama_jenis_publikasi', $this->nama_jenis_publikasi])
            ->andFilterWhere(['like', 'sister_id', $this->sister_id]);


        $query->andWhere('"'.Yii::$app->user->identity->NIY.'" IN  (SELECT NIY FROM publikasi_author pa WHERE pa.pub_id = t.id)');
        if ( ! is_null($this->tanggal_terbit) && strpos($this->tanggal_terbit, ' - ') !== false ) {
            list($start_date, $end_date) = explode(' - ', $this->tanggal_terbit);
            $query->andFilterWhere(['between', 'tanggal_terbit', $start_date, $end_date]);
            $this->tanggal_terbit = null;
        }

        return $dataProvider;
    }
}
