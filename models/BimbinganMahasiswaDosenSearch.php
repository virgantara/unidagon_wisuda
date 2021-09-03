<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BimbinganMahasiswaDosen;

/**
 * BimbinganMahasiswaDosenSearch represents the model behind the search form of `app\models\BimbinganMahasiswaDosen`.
 */
class BimbinganMahasiswaDosenSearch extends BimbinganMahasiswaDosen
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'NIY', 'id_sdm', 'nama', 'kategori_kegiatan', 'bimbingan_mahasiswa_id', 'updated_at', 'created_at'], 'safe'],
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
        $query = BimbinganMahasiswaDosen::find();

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
            ->andFilterWhere(['like', 'NIY', $this->NIY])
            ->andFilterWhere(['like', 'id_sdm', $this->id_sdm])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'kategori_kegiatan', $this->kategori_kegiatan])
            ->andFilterWhere(['like', 'bimbingan_mahasiswa_id', $this->bimbingan_mahasiswa_id]);

        return $dataProvider;
    }
}
