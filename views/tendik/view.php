<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Tendik */

$this->title = $model->NIY;
$this->params['breadcrumbs'][] = ['label' => 'Tenaga Kependidikan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->NIY], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->NIY], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

     <?php
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
      echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
    }
   
    ?>
<div class="row">
    <div class="col-md-3">
    
    Foto Profil<br>

    <img width="180px" src="<?=$model->nIY->foto_path?>" alt="Foto Profil">
    </div>
    <div class="col-md-9">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'NIY',
            'nama',
            'gender',
            'tempat_lahir',
            'tanggal_lahir:date',
            'status_kawin',
            'agama',

        
        ],
    ]) ?>
    </div>
    <div class="col-md-12">
        <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            

            'jenjang_kode',
            'perguruan_tinggi',
            'alamat_kampus:ntext',
            'telp_kampus',
            'fax_kampus',
            'alamat_rumah:ntext',
            'telp_hp',
            [
                'attribute' => 'unit_id',
                'value' => function($data){
                    return !empty($data->unit) ? $data->unit->nama : '-';
                }
            ],
            [
                'attribute' => 'jabatan_id',
                'value' => function($data){
                    return !empty($data->jabatan) ? $data->jabatan->nama : '-';
                }
            ],
        ],
    ]) ?>
    </div>
    </div>
</div>
