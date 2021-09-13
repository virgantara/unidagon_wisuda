<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BahanAjar;

/**
 * BahanAjarSearch represents the model behind the search form of `app\models\BahanAjar`.
 */
class BahanAjarSearch extends BahanAjar
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sister_id', 'judul', 'nama_penerbit', 'isbn', 'tanggal_terbit', 'sk_penugasan', 'tanggal_sk_penugasan', 'nama_jenis', 'id_kategori_kegiatan', 'NIY', 'updated_at', 'created_at'], 'safe'],
            [['id_kategori_capaian_luaran', 'id_penelitian_pengabdian', 'id_jenis_bahan_ajar'], 'integer'],
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
        $query = BahanAjar::find();
        $query->alias('t');
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
            'id_kategori_capaian_luaran' => $this->id_kategori_capaian_luaran,
            'id_penelitian_pengabdian' => $this->id_penelitian_pengabdian,
            'id_jenis_bahan_ajar' => $this->id_jenis_bahan_ajar,
            'tanggal_terbit' => $this->tanggal_terbit,
            'tanggal_sk_penugasan' => $this->tanggal_sk_penugasan,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'sister_id', $this->sister_id])
            ->andFilterWhere(['like', 'judul', $this->judul])
            ->andFilterWhere(['like', 'nama_penerbit', $this->nama_penerbit])
            ->andFilterWhere(['like', 'isbn', $this->isbn])
            ->andFilterWhere(['like', 'sk_penugasan', $this->sk_penugasan])
            ->andFilterWhere(['like', 'nama_jenis', $this->nama_jenis])
            ->andFilterWhere(['like', 'id_kategori_kegiatan', $this->id_kategori_kegiatan]);

        if(!Yii::$app->user->isGuest)
        {
            $niy = Yii::$app->user->identity->NIY;
            $query->andWhere($niy.' IN (SELECT NIY from penulis WHERE penulis.bahan_ajar_id = t.id)');
        }

        

        return $dataProvider;
    }
}
