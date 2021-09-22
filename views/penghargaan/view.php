<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Penghargaan */

$this->title = $model->bentuk;
$this->params['breadcrumbs'][] = ['label' => 'Penghargaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <?= Html::a('Update', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->ID], [
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
            'bentuk',
            'pemberi',
            [
                'attribute' => 'komponen_kegiatan_id',
                'value' => function($data){
                    return !empty($data->komponenKegiatan) ? $data->komponenKegiatan->nama : null;
                }
            ],
            [
                'attribute' => 'kategori_kegiatan_id',
                'value' => function($data){
                    return !empty($data->kategoriKegiatan) ? $data->kategoriKegiatan->nama : '-';
                }
            ],
            [
                'attribute' => 'id_tingkat_penghargaan',
                'value' => function($data){
                    return !empty($data->tingkatPenghargaan) ? $data->tingkatPenghargaan->nama : '-';
                }
            ],
            [
                'attribute' => 'id_jenis_penghargaan',
                'value' => function($data){
                    return !empty($data->jenisPenghargaan) ? $data->jenisPenghargaan->nama : '-';
                }
            ],
            'tahun',
            'tanggal',
            
            [
                'attribute' => 'f_penghargaan',
                'format' => 'raw',
                'value' => function($data){
                    return !empty($data->f_penghargaan) ? Html::a('<i class="fa fa-external-link"></i> Bukti',$data->f_penghargaan,['target'=>'_blank']) : '-';
                }
            ],
            
            'sister_id',
            'updated_at',
            'created_at',
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading">
                
                    <h2>File dari SISTER</h2>
                
            </div>
            <div class="panel-body">
                <h4>
                <ol>
                <?php 

                $sisterFiles = \app\models\SisterFiles::find()->where(['parent_id'=>$model->sister_id])->all();
                foreach($sisterFiles as $file)
                {
                    echo '<li>'.Html::a($file->nama_dokumen,$file->tautan,['target'=>'_blank']).'</li>';
                }
                ?>
                </ol>
                </h4>
            </div>
        </div>
    </div>
</div>