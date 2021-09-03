<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BimbinganMahasiswa;

/**
 * BimbinganMahasiswaSearch represents the model behind the search form of `app\models\BimbinganMahasiswa`.
 */
class BimbinganMahasiswaSearch extends BimbinganMahasiswa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'judul', 'jenis_bimbingan', 'program_studi', 'semester', 'lokasi', 'sk_penugasan', 'tanggal_sk_penugasan', 'keterangan', 'sister_id', 'updated_at', 'created_at'], 'safe'],
            [['komunal'], 'integer'],
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
        $query = BimbinganMahasiswa::find();
        $query->alias('t');

        $query->joinWith(['bimbinganMahasiswaDosens as d']);

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
            'tanggal_sk_penugasan' => $this->tanggal_sk_penugasan,
            'komunal' => $this->komunal,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'judul', $this->judul])
            ->andFilterWhere(['like', 'jenis_bimbingan', $this->jenis_bimbingan])
            ->andFilterWhere(['like', 'program_studi', $this->program_studi])
            ->andFilterWhere(['like', 'semester', $this->semester])
            ->andFilterWhere(['like', 'lokasi', $this->lokasi])
            ->andFilterWhere(['like', 'sk_penugasan', $this->sk_penugasan])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan])
            ->andFilterWhere(['like', 'sister_id', $this->sister_id]);

        $query->andWhere(['d.NIY' => Yii::$app->user->identity->NIY]);

        return $dataProvider;
    }
}
