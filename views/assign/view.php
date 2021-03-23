<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model common\models\Assign */

$this->title = $model->Keterangan;
$this->params['breadcrumbs'][] = ['label' => 'Assignment', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assign-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
            'Keterangan',
            'status',
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => new yii\data\ActiveDataProvider(['query'=>$model->getAssignAssignment()]),
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'ID',
            'NIY',
            'assignmentData.nama',
//            'id_assign',
            'keterangan:ntext',
//            'status',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'filter' => ['verifikasi' => 'Verifikasi', 'diterima' => 'Diterima','ditolak'=>'Ditolak']
            ],
//            'file',
            [
                'attribute'=>'file',
                'format'=>'raw',
                'value' => function($data){
            if(!empty($data->file)){
            return
            Html::a('View', ['assignment/display', 'id' => $data->ID],['class' => 'btn btn-warning']).'&nbsp;&nbsp;'.
            Html::a('Download', ['assignment/download', 'id' => $data->ID],['class' => 'btn btn-primary']);
            }
            else
            {
            return
            "<p class='btn btn-danger' align='center'>No File</p>";
            }
            }
            ],
            
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} ',
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to(['assignment/'.$action, 'id' => $model->ID]);
                }
            ],
//
//            ['class' => 'yii\grid\ActionColumn',
//            'template'=>'{update}&nbsp;&nbsp;{delete}'],
        ],
    ]); ?>
    
</div>
