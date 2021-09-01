<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PenelitianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Penelitian';
$this->params['breadcrumbs'][] = $this->title;

$list_jenis_sumber_dana = \app\helpers\MyHelper::listJenisSumberDana();

?>

<h3><?= Html::encode($this->title) ?></h3>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
<div class="panel-body ">
    <p>
        <?= Html::a('Create Penelitian', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php 
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
      echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
    }
    ?>
    <?php
    $gridColumns = [
    [
        'class'=>'kartik\grid\SerialColumn',
        'contentOptions'=>['class'=>'kartik-sheet-style'],
        'width'=>'36px',
        'pageSummary'=>'Total',
        'pageSummaryOptions' => ['colspan' => 6],
        'header'=>'',
        'headerOptions'=>['class'=>'kartik-sheet-style']
    ],
            'judul_penelitian_pengabdian',
            'tahun_kegiatan',
            // 'status',
            
            //'nilai',
            //'sister_id',
            'nama_skim',
            'durasi_kegiatan',
            'tempat_kegiatan',
            'dana_dikti',
            'dana_institusi_lain',
            'dana_pt',
            //'tahun_usulan',
            //'tahun_dilaksanakan',
            'tahun_pelaksanaan_ke',
            [
                'attribute' => 'jenis_sumber_dana',
                'filter' => $list_jenis_sumber_dana,
                'value' => function($data) use ($list_jenis_sumber_dana){
                    return $list_jenis_sumber_dana[$data->jenis_sumber_dana];
                }
            ],
            //'no_sk_tugas',
            //'tgl_sk_tugas',
            //'kategori_kegiatan_id',
            //'skim_kegiatan_id',
            //'kelompok_bidang_id',
            //'komponen_kegiatan_id',
            //'updated_at',
            //'created_at',
    ['class' => 'yii\grid\ActionColumn']
];?>    
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'responsiveWrap' => false,
        'columns' => $gridColumns,
        'containerOptions' => ['style' => 'overflow: auto'], 
        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'containerOptions' => ['style'=>'overflow: auto'], 
        'beforeHeader'=>[
            [
                'columns'=>[
                    ['content'=> $this->title, 'options'=>['colspan'=>14, 'class'=>'text-center warning']], //cuma satu 
                ], 
                'options'=>['class'=>'skip-export'] 
            ]
        ],
        'exportConfig' => [
              GridView::PDF => ['label' => 'Save as PDF'],
              GridView::EXCEL => ['label' => 'Save as EXCEL'], //untuk menghidupkan button export ke Excell
              GridView::HTML => ['label' => 'Save as HTML'], //untuk menghidupkan button export ke HTML
              GridView::CSV => ['label' => 'Save as CSV'], //untuk menghidupkan button export ke CVS
          ],
          
        'toolbar' =>  [
            '{export}', 

           '{toggleData}' //uncoment untuk menghidupkan button menampilkan semua data..
        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
    // set export properties
        'export' => [
            'fontAwesome' => true
        ],
        'pjax' => true,
        'bordered' => true,
        'striped' => true,
        // 'condensed' => false,
        // 'responsive' => false,
        'hover' => true,
        // 'floatHeader' => true,
        // 'showPageSummary' => true, //true untuk menjumlahkan nilai di suatu kolom, kebetulan pada contoh tidak ada angka.
        'panel' => [
            'type' => GridView::TYPE_PRIMARY
        ],
    ]); ?>

</div>
        </div>
    </div>

</div>

