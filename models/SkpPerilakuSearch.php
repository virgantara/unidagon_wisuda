<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SkpPerilaku;

/**
 * SkpPerilakuSearch represents the model behind the search form of `app\models\SkpPerilaku`.
 */
class SkpPerilakuSearch extends SkpPerilaku
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'skp_id', 'updated_at', 'created_at'], 'safe'],
            [['orientasi', 'integritas', 'komitmen', 'disiplin', 'kerjasama', 'kepemimpinan', 'total', 'rata_rata'], 'number'],
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
        $query = SkpPerilaku::find();

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
            'orientasi' => $this->orientasi,
            'integritas' => $this->integritas,
            'komitmen' => $this->komitmen,
            'disiplin' => $this->disiplin,
            'kerjasama' => $this->kerjasama,
            'kepemimpinan' => $this->kepemimpinan,
            'total' => $this->total,
            'rata_rata' => $this->rata_rata,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'skp_id', $this->skp_id]);

        return $dataProvider;
    }
}
