<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PesertaSyarat;

/**
 * PesertaSyaratSearch represents the model behind the search form of `app\models\PesertaSyarat`.
 */
class PesertaSyaratSearch extends PesertaSyarat
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'peserta_id', 'syarat_id'], 'integer'],
            [['file_path'], 'safe'],
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
        $query = PesertaSyarat::find();

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
            'peserta_id' => $this->peserta_id,
            'syarat_id' => $this->syarat_id,
        ]);

        $query->andFilterWhere(['like', 'file_path', $this->file_path]);

        return $dataProvider;
    }
}
