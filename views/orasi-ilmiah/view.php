<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OrasiIlmiah */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orasi Ilmiahs', 'url' => ['index']];
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
            'NIY',
            'sister_id',
            'nama_kategori_kegiatan',
            'nama_kategori_pencapaian',
            'id_kategori_capaian_luaran',
            'kategori_kegiatan_id',
            'judul_buku_makalah',
            'nama_pertemuan_ilmiah',
            'penyelenggara_kegiatan',
            'tanggal_pelaksanaan',
            'id_kategori_pembicara',
            'no_sk_tugas',
            'tanggal_sk_penugasan',
            'bahasa',
            'updated_at',
            'created_at',
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>