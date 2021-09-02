<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Buku;

/**
 * BukuSearch represents the model behind the search form of `app\models\Buku`.
 */
class BukuSearch extends Buku
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'tahun', 'jenis_luaran_id', 'halaman'], 'integer'],
            [['NIY', 'judul', 'penerbit', 'f_karya', 'ISBN', 'vol', 'link', 'ver', 'komentar', 'jenis_litab', 'parent_id', 'uuid', 'tanggal_terbit', 'no_sk_tugas', 'tanggal_sk_penugasan', 'sister_id', 'id_kategori_capaian_luaran', 'id_jenis_bahan_ajar', 'nama_kategori_kegiatan', 'updated_at', 'created_at'], 'safe'],
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
        $query = Buku::find();
        $query->alias('p');
        $query->where(['p.NIY' => Yii::$app->user->identity->NIY]);
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
            'ID' => $this->ID,
            'tahun' => $this->tahun,
            'jenis_luaran_id' => $this->jenis_luaran_id,
            'halaman' => $this->halaman,
            'tanggal_terbit' => $this->tanggal_terbit,
            'tanggal_sk_penugasan' => $this->tanggal_sk_penugasan,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'NIY', $this->NIY])
            ->andFilterWhere(['like', 'judul', $this->judul])
            ->andFilterWhere(['like', 'penerbit', $this->penerbit])
            ->andFilterWhere(['like', 'f_karya', $this->f_karya])
            ->andFilterWhere(['like', 'ISBN', $this->ISBN])
            ->andFilterWhere(['like', 'vol', $this->vol])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'ver', $this->ver])
            ->andFilterWhere(['like', 'komentar', $this->komentar])
            ->andFilterWhere(['like', 'jenis_litab', $this->jenis_litab])
            ->andFilterWhere(['like', 'parent_id', $this->parent_id])
            ->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'no_sk_tugas', $this->no_sk_tugas])
            ->andFilterWhere(['like', 'sister_id', $this->sister_id])
            ->andFilterWhere(['like', 'id_kategori_capaian_luaran', $this->id_kategori_capaian_luaran])
            ->andFilterWhere(['like', 'id_jenis_bahan_ajar', $this->id_jenis_bahan_ajar])
            ->andFilterWhere(['like', 'nama_kategori_kegiatan', $this->nama_kategori_kegiatan]);

        return $dataProvider;
    }
}
