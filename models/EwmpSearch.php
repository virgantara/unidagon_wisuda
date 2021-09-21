<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ewmp;

/**
 * EwmpSearch represents the model behind the search form of `app\models\Ewmp`.
 */
class EwmpSearch extends Ewmp
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'NIY', 'is_dtps', 'updated_at', 'created_at'], 'safe'],
            [['pendidikan_sks_ps', 'pendidikan_sks_ps_lain', 'pendidikan_sks_pt_lain', 'penelitian', 'abdimas', 'penunjang', 'total_sks'], 'number'],
            [['tahun_akademik'], 'integer'],
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
        $query = Ewmp::find();

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
            'pendidikan_sks_ps' => $this->pendidikan_sks_ps,
            'pendidikan_sks_ps_lain' => $this->pendidikan_sks_ps_lain,
            'pendidikan_sks_pt_lain' => $this->pendidikan_sks_pt_lain,
            'penelitian' => $this->penelitian,
            'abdimas' => $this->abdimas,
            'penunjang' => $this->penunjang,
            'total_sks' => $this->total_sks,
            'tahun_akademik' => $this->tahun_akademik,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'NIY', $this->NIY])
            ->andFilterWhere(['like', 'is_dtps', $this->is_dtps]);

        return $dataProvider;
    }
}
