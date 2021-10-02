<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Sertifikasi;

/**
 * SertifikasiSearch represents the model behind the search form of `app\models\Sertifikasi`.
 */
class SertifikasiSearch extends Sertifikasi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'jenis_sertifikasi', 'bidang_studi', 'sk_sertifikasi', 'nomor_registrasi', 'NIY', 'sister_id', 'updated_at', 'created_at'], 'safe'],
            [['tahun_sertifikasi', 'id_jenis_sertifikasi', 'id_bidang_studi'], 'integer'],
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
        $query = Sertifikasi::find();
        $query->alias('p');
        $query->where(['p.NIY' => Yii::$app->user->identity->NIY]);
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
            'tahun_sertifikasi' => $this->tahun_sertifikasi,
            'id_jenis_sertifikasi' => $this->id_jenis_sertifikasi,
            'id_bidang_studi' => $this->id_bidang_studi,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'jenis_sertifikasi', $this->jenis_sertifikasi])
            ->andFilterWhere(['like', 'bidang_studi', $this->bidang_studi])
            ->andFilterWhere(['like', 'sk_sertifikasi', $this->sk_sertifikasi])
            ->andFilterWhere(['like', 'nomor_registrasi', $this->nomor_registrasi])
            ->andFilterWhere(['like', 'NIY', $this->NIY])
            ->andFilterWhere(['like', 'sister_id', $this->sister_id]);

        return $dataProvider;
    }
}
