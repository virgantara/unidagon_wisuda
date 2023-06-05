<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Peserta;

/**
 * PesertaSearch represents the model behind the search form of `app\models\Peserta`.
 */
class PesertaSearch extends Peserta
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'periode_id'], 'integer'],
            [['nim', 'nama_lengkap', 'fakultas', 'prodi', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'status_warga', 'warga_negara', 'alamat', 'no_telp', 'nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu', 'pas_photo', 'ijazah', 'akta_kelahiran', 'kwitansi_jilid', 'surat_bebas_pinjaman', 'resume_skripsi', 'surat_bebas_tunggakan', 'transkrip', 'skl_tahfidz', 'kwitansi_wisuda', 'tanda_keluar_asrama', 'surat_jalan', 'skripsi', 'abstrak', 'kode_pendaftaran', 'kampus', 'status_validasi', 'kmi', 'bukti_revisi_bahasa', 'jumlah_rombongan', 'bukti_layouter', 'bukti_perpus', 'created', 'drive_path','nik', 'ukuran_kaos'], 'safe'],
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
        // $periode = Periode::findOne(['buka' => 'Y']);
        $query = Peserta::find();
        $query->joinWith(['periode as p']);
        $query->where(['p.status_aktivasi' => 'Y']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created'=>SORT_DESC]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'nim' => $this->nim,
            'nik' => $this->nik,
            'periode_id' => $this->periode_id,
        ]);

        $query->andFilterWhere(['like', 'nim', $this->nim])
            ->andFilterWhere(['like', 'nama_lengkap', $this->nama_lengkap])
            ->andFilterWhere(['like', 'fakultas', $this->fakultas])
            ->andFilterWhere(['like', 'prodi', $this->prodi])
            ->andFilterWhere(['like', 'tempat_lahir', $this->tempat_lahir])
            ->andFilterWhere(['like', 'tanggal_lahir', $this->tanggal_lahir])
            ->andFilterWhere(['like', 'jenis_kelamin', $this->jenis_kelamin])
            ->andFilterWhere(['like', 'status_warga', $this->status_warga])
            ->andFilterWhere(['like', 'warga_negara', $this->warga_negara])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'no_telp', $this->no_telp])
            ->andFilterWhere(['like', 'nama_ayah', $this->nama_ayah])
            ->andFilterWhere(['like', 'pekerjaan_ayah', $this->pekerjaan_ayah])
            ->andFilterWhere(['like', 'nama_ibu', $this->nama_ibu])
            ->andFilterWhere(['like', 'pekerjaan_ibu', $this->pekerjaan_ibu])
            ->andFilterWhere(['like', 'pas_photo', $this->pas_photo])
            ->andFilterWhere(['like', 'ijazah', $this->ijazah])
            ->andFilterWhere(['like', 'akta_kelahiran', $this->akta_kelahiran])
            ->andFilterWhere(['like', 'kwitansi_jilid', $this->kwitansi_jilid])
            ->andFilterWhere(['like', 'surat_bebas_pinjaman', $this->surat_bebas_pinjaman])
            ->andFilterWhere(['like', 'resume_skripsi', $this->resume_skripsi])
            ->andFilterWhere(['like', 'surat_bebas_tunggakan', $this->surat_bebas_tunggakan])
            ->andFilterWhere(['like', 'transkrip', $this->transkrip])
            ->andFilterWhere(['like', 'skl_tahfidz', $this->skl_tahfidz])
            ->andFilterWhere(['like', 'kwitansi_wisuda', $this->kwitansi_wisuda])
            ->andFilterWhere(['like', 'tanda_keluar_asrama', $this->tanda_keluar_asrama])
            ->andFilterWhere(['like', 'surat_jalan', $this->surat_jalan])
            ->andFilterWhere(['like', 'skripsi', $this->skripsi])
            ->andFilterWhere(['like', 'abstrak', $this->abstrak])
            ->andFilterWhere(['like', 'kode_pendaftaran', $this->kode_pendaftaran])
            ->andFilterWhere(['like', 'kampus', $this->kampus])
            ->andFilterWhere(['like', 'status_validasi', $this->status_validasi])
            ->andFilterWhere(['like', 'kmi', $this->kmi])
            ->andFilterWhere(['like', 'bukti_revisi_bahasa', $this->bukti_revisi_bahasa])
            ->andFilterWhere(['like', 'bukti_layouter', $this->bukti_layouter])
            ->andFilterWhere(['like', 'bukti_perpus', $this->bukti_perpus])
            ->andFilterWhere(['like', 'drive_path', $this->drive_path])
            ->andFilterWhere(['like', 'ukuran_kaos', $this->drive_path])
            ->andFilterWhere(['like', 'jumlah_rombongan', $this->jumlah_rombongan]);

        return $dataProvider;
    }

    public function searchRiwayat($params)
    {
        // $periode = Periode::findOne(['buka' => 'Y']);
        $query = Peserta::find();
        // $query->joinWith(['periode as p']);
        // $query->where(['p.status_aktivasi' => 'Y']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created'=>SORT_DESC]
            ],
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
            'created' => $this->created,
            'periode_id' => $this->periode_id,
        ]);

        $query->andFilterWhere(['like', 'nim', $this->nim])
            ->andFilterWhere(['like', 'nama_lengkap', $this->nama_lengkap])
            ->andFilterWhere(['like', 'fakultas', $this->fakultas])
            ->andFilterWhere(['like', 'prodi', $this->prodi])
            ->andFilterWhere(['like', 'tempat_lahir', $this->tempat_lahir])
            ->andFilterWhere(['like', 'tanggal_lahir', $this->tanggal_lahir])
            ->andFilterWhere(['like', 'jenis_kelamin', $this->jenis_kelamin])
            ->andFilterWhere(['like', 'status_warga', $this->status_warga])
            ->andFilterWhere(['like', 'warga_negara', $this->warga_negara])
            ->andFilterWhere(['like', 'alamat', $this->alamat])
            ->andFilterWhere(['like', 'no_telp', $this->no_telp])
            ->andFilterWhere(['like', 'nama_ayah', $this->nama_ayah])
            ->andFilterWhere(['like', 'pekerjaan_ayah', $this->pekerjaan_ayah])
            ->andFilterWhere(['like', 'nama_ibu', $this->nama_ibu])
            ->andFilterWhere(['like', 'pekerjaan_ibu', $this->pekerjaan_ibu])
            ->andFilterWhere(['like', 'pas_photo', $this->pas_photo])
            ->andFilterWhere(['like', 'ijazah', $this->ijazah])
            ->andFilterWhere(['like', 'akta_kelahiran', $this->akta_kelahiran])
            ->andFilterWhere(['like', 'kwitansi_jilid', $this->kwitansi_jilid])
            ->andFilterWhere(['like', 'surat_bebas_pinjaman', $this->surat_bebas_pinjaman])
            ->andFilterWhere(['like', 'resume_skripsi', $this->resume_skripsi])
            ->andFilterWhere(['like', 'surat_bebas_tunggakan', $this->surat_bebas_tunggakan])
            ->andFilterWhere(['like', 'transkrip', $this->transkrip])
            ->andFilterWhere(['like', 'skl_tahfidz', $this->skl_tahfidz])
            ->andFilterWhere(['like', 'kwitansi_wisuda', $this->kwitansi_wisuda])
            ->andFilterWhere(['like', 'tanda_keluar_asrama', $this->tanda_keluar_asrama])
            ->andFilterWhere(['like', 'surat_jalan', $this->surat_jalan])
            ->andFilterWhere(['like', 'skripsi', $this->skripsi])
            ->andFilterWhere(['like', 'abstrak', $this->abstrak])
            ->andFilterWhere(['like', 'kode_pendaftaran', $this->kode_pendaftaran])
            ->andFilterWhere(['like', 'kampus', $this->kampus])
            ->andFilterWhere(['like', 'status_validasi', $this->status_validasi])
            ->andFilterWhere(['like', 'kmi', $this->kmi])
            ->andFilterWhere(['like', 'bukti_revisi_bahasa', $this->bukti_revisi_bahasa])
            ->andFilterWhere(['like', 'bukti_layouter', $this->bukti_layouter])
            ->andFilterWhere(['like', 'bukti_perpus', $this->bukti_perpus])
            ->andFilterWhere(['like', 'drive_path', $this->drive_path])
            ->andFilterWhere(['like', 'ukuran_kaos', $this->drive_path])
            ->andFilterWhere(['like', 'jumlah_rombongan', $this->jumlah_rombongan]);

        return $dataProvider;
    }
}
