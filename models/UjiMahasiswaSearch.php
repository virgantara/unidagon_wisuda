<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UjiMahasiswa;

/**
 * UjiMahasiswaSearch represents the model behind the search form of `app\models\UjiMahasiswa`.
 */
class UjiMahasiswaSearch extends UjiMahasiswa
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_aktivitas', 'judul', 'id_uji', 'id_kategori_kegiatan', 'nama_kategori_kegiatan', 'id_dosen', 'NIY', 'updated_at', 'created_at','id_semester','lokasi','sk_tugas','tanggal_sk_tugas','nama_jenis_aktivitas'], 'safe'],
            [['penguji_ke'], 'integer'],
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
        $query = UjiMahasiswa::find();

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
            'penguji_ke' => $this->penguji_ke,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
            'id_semester' => $this->id_semester
        ]);

        $query->andFilterWhere(['like', 'lokasi', $this->lokasi])
            ->andFilterWhere(['like', 'id_aktivitas', $this->id_aktivitas])
            ->andFilterWhere(['like', 'judul', $this->judul])
            ->andFilterWhere(['like', 'nama_jenis_aktivitas', $this->nama_jenis_aktivitas])
            ->andFilterWhere(['like', 'id_uji', $this->id_uji])
            ->andFilterWhere(['like', 'sk_tugas', $this->sk_tugas])
            ->andFilterWhere(['like', 'id_kategori_kegiatan', $this->id_kategori_kegiatan])
            ->andFilterWhere(['like', 'nama_kategori_kegiatan', $this->nama_kategori_kegiatan])
            ->andFilterWhere(['like', 'id_dosen', $this->id_dosen])
            ->andFilterWhere(['like', 'NIY', $this->NIY]);

        if ( ! is_null($this->tanggal_sk_tugas) && strpos($this->tanggal_sk_tugas, ' - ') !== false ) {
            list($start_date, $end_date) = explode(' - ', $this->tanggal_sk_tugas);
            $query->andFilterWhere(['between', 'tanggal_sk_tugas', $start_date, $end_date]);
            $this->tanggal_sk_tugas = null;
        }

        return $dataProvider;
    }
}
