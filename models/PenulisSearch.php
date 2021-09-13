<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Penulis;

/**
 * PenulisSearch represents the model behind the search form of `app\models\Penulis`.
 */
class PenulisSearch extends Penulis
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bahan_ajar_id', 'NIY', 'nama', 'afiliasi', 'peran', 'jenis', 'id_sdm', 'updated_at', 'created_at'], 'safe'],
            [['urutan'], 'integer'],
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
        $query = Penulis::find();

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
            'urutan' => $this->urutan,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'bahan_ajar_id', $this->bahan_ajar_id])
            ->andFilterWhere(['like', 'NIY', $this->NIY])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'afiliasi', $this->afiliasi])
            ->andFilterWhere(['like', 'peran', $this->peran])
            ->andFilterWhere(['like', 'jenis', $this->jenis])
            ->andFilterWhere(['like', 'id_sdm', $this->id_sdm]);

        return $dataProvider;
    }
}
