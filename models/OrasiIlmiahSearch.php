<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrasiIlmiah;

/**
 * OrasiIlmiahSearch represents the model behind the search form of `app\models\OrasiIlmiah`.
 */
class OrasiIlmiahSearch extends OrasiIlmiah
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_kategori_capaian_luaran', 'id_kategori_pembicara'], 'integer'],
            [['NIY', 'nama_kategori_kegiatan', 'nama_kategori_pencapaian', 'kategori_kegiatan_id', 'judul_buku_makalah', 'nama_pertemuan_ilmiah', 'penyelenggara_kegiatan', 'tanggal_pelaksanaan', 'no_sk_tugas', 'tanggal_sk_penugasan', 'bahasa', 'sister_id', 'tingkat_pertemuan_id', 'updated_at', 'created_at'], 'safe'],
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
        $query = OrasiIlmiah::find();

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
            'tanggal_pelaksanaan' => $this->tanggal_pelaksanaan,
            'tanggal_sk_penugasan' => $this->tanggal_sk_penugasan,
            'id_kategori_capaian_luaran' => $this->id_kategori_capaian_luaran,
            'id_kategori_pembicara' => $this->id_kategori_pembicara,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'NIY', $this->NIY])
            ->andFilterWhere(['like', 'nama_kategori_kegiatan', $this->nama_kategori_kegiatan])
            ->andFilterWhere(['like', 'nama_kategori_pencapaian', $this->nama_kategori_pencapaian])
            ->andFilterWhere(['like', 'kategori_kegiatan_id', $this->kategori_kegiatan_id])
            ->andFilterWhere(['like', 'judul_buku_makalah', $this->judul_buku_makalah])
            ->andFilterWhere(['like', 'nama_pertemuan_ilmiah', $this->nama_pertemuan_ilmiah])
            ->andFilterWhere(['like', 'penyelenggara_kegiatan', $this->penyelenggara_kegiatan])
            ->andFilterWhere(['like', 'no_sk_tugas', $this->no_sk_tugas])
            ->andFilterWhere(['like', 'bahasa', $this->bahasa])
            ->andFilterWhere(['like', 'sister_id', $this->sister_id])
            ->andFilterWhere(['like', 'tingkat_pertemuan_id', $this->tingkat_pertemuan_id]);

        $query->andWhere(['NIY' => Yii::$app->user->identity->NIY]);

        return $dataProvider;
    }
}
