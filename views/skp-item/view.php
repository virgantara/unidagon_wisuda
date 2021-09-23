<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SkpItem */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Skp Items', 'url' => ['index']];
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
            'skp_id',
            'komponen_kegiatan_id',
            'target_ak',
            'target_qty',
            'target_satuan',
            'target_mutu',
            'target_waktu',
            'target_waktu_satuan',
            'target_biaya',
            'realisasi_ak',
            'realisasi_qty',
            'realisasi_satuan',
            'realisasi_mutu',
            'realisasi_waktu',
            'realisasi_waktu_satuan',
            'realisasi_biaya',
            'capaian',
            'capaian_skp',
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>