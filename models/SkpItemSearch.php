<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SkpItem;

/**
 * SkpItemSearch represents the model behind the search form of `app\models\SkpItem`.
 */
class SkpItemSearch extends SkpItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'skp_id', 'target_satuan', 'target_waktu_satuan', 'realisasi_satuan', 'realisasi_mutu', 'realisasi_waktu_satuan'], 'safe'],
            [['komponen_kegiatan_id'], 'integer'],
            [['target_ak', 'target_qty', 'target_mutu', 'target_waktu', 'target_biaya', 'realisasi_ak', 'realisasi_qty', 'realisasi_waktu', 'realisasi_biaya', 'capaian', 'capaian_skp'], 'number'],
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
        $query = SkpItem::find();

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
            'komponen_kegiatan_id' => $this->komponen_kegiatan_id,
            'target_ak' => $this->target_ak,
            'target_qty' => $this->target_qty,
            'target_mutu' => $this->target_mutu,
            'target_waktu' => $this->target_waktu,
            'target_biaya' => $this->target_biaya,
            'realisasi_ak' => $this->realisasi_ak,
            'realisasi_qty' => $this->realisasi_qty,
            'realisasi_waktu' => $this->realisasi_waktu,
            'realisasi_biaya' => $this->realisasi_biaya,
            'capaian' => $this->capaian,
            'capaian_skp' => $this->capaian_skp,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'skp_id', $this->skp_id])
            ->andFilterWhere(['like', 'target_satuan', $this->target_satuan])
            ->andFilterWhere(['like', 'target_waktu_satuan', $this->target_waktu_satuan])
            ->andFilterWhere(['like', 'realisasi_satuan', $this->realisasi_satuan])
            ->andFilterWhere(['like', 'realisasi_mutu', $this->realisasi_mutu])
            ->andFilterWhere(['like', 'realisasi_waktu_satuan', $this->realisasi_waktu_satuan]);

        return $dataProvider;
    }
}
