<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VisitingScientist;

/**
 * VisitingScientistSearch represents the model behind the search form of `app\models\VisitingScientist`.
 */
class VisitingScientistSearch extends VisitingScientist
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'durasi_kegiatan', 'id_kategori_capaian_luaran', 'durasi'], 'integer'],
            [['perguruan_tinggi_pengundang', 'tanggal_pelaksanaan', 'kategori_kegiatan_id', 'nama_penelitian_pengabdian', 'id_penelitian_pengabdian', 'nama_kategori_pencapaian', 'id_universitas', 'kegiatan_penting_yang_dilakukan', 'no_sk_tugas', 'tanggal_sk_penugasan', 'NIY', 'sister_id', 'updated_at', 'created_at'], 'safe'],
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
        $query = VisitingScientist::find();

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
            'durasi_kegiatan' => $this->durasi_kegiatan,
            'tanggal_pelaksanaan' => $this->tanggal_pelaksanaan,
            'id_kategori_capaian_luaran' => $this->id_kategori_capaian_luaran,
            'tanggal_sk_penugasan' => $this->tanggal_sk_penugasan,
            'durasi' => $this->durasi,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'perguruan_tinggi_pengundang', $this->perguruan_tinggi_pengundang])
            ->andFilterWhere(['like', 'kategori_kegiatan_id', $this->kategori_kegiatan_id])
            ->andFilterWhere(['like', 'nama_penelitian_pengabdian', $this->nama_penelitian_pengabdian])
            ->andFilterWhere(['like', 'id_penelitian_pengabdian', $this->id_penelitian_pengabdian])
            ->andFilterWhere(['like', 'nama_kategori_pencapaian', $this->nama_kategori_pencapaian])
            ->andFilterWhere(['like', 'id_universitas', $this->id_universitas])
            ->andFilterWhere(['like', 'kegiatan_penting_yang_dilakukan', $this->kegiatan_penting_yang_dilakukan])
            ->andFilterWhere(['like', 'no_sk_tugas', $this->no_sk_tugas])
            ->andFilterWhere(['like', 'NIY', $this->NIY])
            ->andFilterWhere(['like', 'sister_id', $this->sister_id]);

        return $dataProvider;
    }
}
