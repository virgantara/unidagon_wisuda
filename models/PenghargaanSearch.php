<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Penghargaan;

/**
 * PenghargaanSearch represents the model behind the search form of `app\models\Penghargaan`.
 */
class PenghargaanSearch extends Penghargaan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'tahun'], 'integer'],
            [['NIY', 'bentuk', 'pemberi', 'f_penghargaan', 'ver', 'sister_id', 'updated_at', 'created_at'], 'safe'],
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
        $query = Penghargaan::find();

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
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'NIY', $this->NIY])
            ->andFilterWhere(['like', 'bentuk', $this->bentuk])
            ->andFilterWhere(['like', 'pemberi', $this->pemberi])
            ->andFilterWhere(['like', 'f_penghargaan', $this->f_penghargaan])
            ->andFilterWhere(['like', 'ver', $this->ver])
            ->andFilterWhere(['like', 'sister_id', $this->sister_id]);

        return $dataProvider;
    }
}
