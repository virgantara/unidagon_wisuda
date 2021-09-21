<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PenunjangLain */

$this->title = $model->nama_kegiatan;
$this->params['breadcrumbs'][] = ['label' => 'Penunjang Lains', 'url' => ['index']];
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
      <?php 
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
      echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
    }
    ?>
<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'kategori_kegiatan_id',
                'value' => function($data){
                    return !empty($data->kategoriKegiatan) ? $data->kategoriKegiatan->nama : '-';
                }
            ],
            [
                'attribute' => 'komponen_kegiatan_id',
                'value' => function($data){
                    return !empty($data->komponenKegiatan) ? $data->komponenKegiatan->nama : '-';
                }
            ],
            [
                'attribute' => 'jenis_panitia_id',
                'value' => function($data){
                    return !empty($data->jenisPanitia) ? $data->jenisPanitia->nama : '-';
                }
            ],
            [
                'attribute' => 'tingkat_id',
                'value' => function($data){
                    return !empty($data->tingkat) ? $data->tingkat->nama : '-';
                }
            ],
            'nama_kegiatan',
            'instansi',
            'no_sk_tugas',
            'tanggal_mulai:date',
            'tanggal_selesai:date',
            [
                'attribute' => 'file_path',
                'format' => 'raw',
                'value' => function($data){
                    return !empty($data->file_path) ? Html::a('<i class="fa fa-external-link"></i> Bukti',$data->file_path,['target'=>'_blank']) : '-';
                }
            ],
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>