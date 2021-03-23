<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Pengajaran */
Yii::$app->setHomeUrl(['/site/homelog']);
$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Pengajaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengajaran-view">

    

    <p>
        <?= Html::a('Edit', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'NIY',
            'matkul:ntext',
            'program_pendidikan',
            'jurusan',
            'institusi',
            'program',
            'tahun_awal',
            'tahun_akhir',
             [
                'attribute'=>'f_penugasan',
                'format'=>'raw',
                'value' => function($data){
            if(!empty($data->f_penugasan)){
            return
            Html::a('View', ['pengajaran/display', 'id' => $data->ID],['class' => 'btn btn-warning']).'&nbsp;&nbsp;'.
            Html::a('Download', ['pengajaran/download', 'id' => $data->ID],['class' => 'btn btn-primary']);
            }
            else
            {
            return
            "<p class='btn btn-danger' align='center'>No File</p>";
            }
            }
            ],
            'ver',
        ],
    ]) ?>

</div>
