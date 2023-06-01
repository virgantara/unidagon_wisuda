<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Peserta */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pesertas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>

            <div class="panel-body ">

                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'nim',
                        'nama_lengkap',
                        'fakultas',
                        'prodi',
                        'tempat_lahir',
                        'tanggal_lahir',
                        'jenis_kelamin',
                        'status_warga',
                        'warga_negara',
                        'alamat:ntext',
                        'no_telp',
                        'nama_ayah',
                        'pekerjaan_ayah',
                        'nama_ibu',
                        'pekerjaan_ibu',
                        'pas_photo',
                        'ijazah',
                        'akta_kelahiran',
                        'kwitansi_jilid',
                        'surat_bebas_pinjaman',
                        'resume_skripsi',
                        'surat_bebas_tunggakan',
                        'transkrip',
                        'skl_tahfidz',
                        'kwitansi_wisuda',
                        'tanda_keluar_asrama',
                        'surat_jalan',
                        'skripsi',
                        'abstrak',
                        'kode_pendaftaran',
                        'kampus',
                        'status_validasi',
                        'kmi',
                        'bukti_revisi_bahasa',
                        'bukti_layouter',
                        'bukti_perpus',
                        'created',
                        'periode_id',
                        'drive_path:ntext',
                    ],
                ]) ?>

            </div>
        </div>

    </div>
</div>