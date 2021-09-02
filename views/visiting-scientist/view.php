<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\VisitingScientist */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Visiting Scientists', 'url' => ['index']];
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
            'perguruan_tinggi_pengundang',
            'durasi_kegiatan',
            'tanggal_pelaksanaan',
            'kategori_kegiatan_id',
            'nama_penelitian_pengabdian',
            'id_penelitian_pengabdian',
            'nama_kategori_pencapaian',
            'id_kategori_capaian_luaran',
            'id_universitas',
            'kegiatan_penting_yang_dilakukan',
            'no_sk_tugas',
            'tanggal_sk_penugasan',
            'durasi',
            'tingkat',
            'NIY',
            'sister_id',
            'updated_at',
            'created_at',
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>