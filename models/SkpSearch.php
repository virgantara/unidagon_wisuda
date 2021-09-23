<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Skp;

/**
 * SkpSearch represents the model behind the search form of `app\models\Skp`.
 */
class SkpSearch extends Skp
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pejabat_penilai', 'pegawai_dinilai', 'status_skp', 'updated_at', 'created_at'], 'safe'],
            [['periode_id'], 'integer'],
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
        $query = Skp::find();
        $query->alias('t');
        $query->where(['pegawai_dinilai'=>Yii::$app->user->identity->NIY]);

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
            'periode_id' => $this->periode_id,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'pejabat_penilai', $this->pejabat_penilai])
            ->andFilterWhere(['like', 'pegawai_dinilai', $this->pegawai_dinilai])
            ->andFilterWhere(['like', 'status_skp', $this->status_skp]);

        return $dataProvider;
    }
}
