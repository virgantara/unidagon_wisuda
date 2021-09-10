<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UjiMahasiswa */

$this->title = $model->judul;
$this->params['breadcrumbs'][] = ['label' => 'Uji Mahasiswas', 'url' => ['index']];
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
            // 'id_aktivitas',
            'judul',
            'nama_jenis_aktivitas',
            // 'id_uji',
            [
                'attribute' => 'id_kategori_kegiatan',
                'value' => function($data){
                    return !empty($data->kategoriKegiatan) ? $data->kategoriKegiatan->nama : '-';
                }
            ],
            'id_semester',
            'lokasi',
            'sk_tugas',
            'tanggal_sk_tugas',
            
            //'nama_kategori_kegiatan',
            //'id_dosen',
            //'NIY',
            'penguji_ke',
            // 'id_uji',
            // 'id_dosen',
            // 'NIY',
            // 'penguji_ke',
            'updated_at',
            'created_at',
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>