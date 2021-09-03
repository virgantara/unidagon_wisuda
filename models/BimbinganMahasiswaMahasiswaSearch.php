<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BimbinganMahasiswaMahasiswa;

/**
 * BimbinganMahasiswaMahasiswaSearch represents the model behind the search form of `app\models\BimbinganMahasiswaMahasiswa`.
 */
class BimbinganMahasiswaMahasiswaSearch extends BimbinganMahasiswaMahasiswa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'nomor_induk', 'nama', 'peran', 'bimbingan_mahasiswa_id', 'updated_at', 'created_at'], 'safe'],
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
        $query = BimbinganMahasiswaMahasiswa::find();

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
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'nomor_induk', $this->nomor_induk])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'peran', $this->peran])
            ->andFilterWhere(['like', 'bimbingan_mahasiswa_id', $this->bimbingan_mahasiswa_id]);

        return $dataProvider;
    }
}
