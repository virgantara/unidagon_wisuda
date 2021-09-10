<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\VisitingScientistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Visiting Scientists';
$this->params['breadcrumbs'][] = $this->title;

$list_tingkat = ArrayHelper::map(\app\models\Tingkat::find()->all(),'id','nama');

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
        <?= Html::a('Create Visiting Scientist', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
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
            // 'id',
            'perguruan_tinggi_pengundang',
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'durasi_kegiatan',
                'editableOptions' => [
                    'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                    'asPopover' => false
                ],
                
            ],
            'tanggal_pelaksanaan',
            // 'tingkat',
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'tingkat',
                'filter' => $list_tingkat,
                'refreshGrid' => true,
                'editableOptions' => [
                    'data' => $list_tingkat,
                    'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                    'asPopover' => false
                ],
                'value' => function($data){
                    return !empty($data->tingkat0) ? $data->tingkat0->nama : '-';
                }
            ],
            // 'kategori_kegiatan_id',
            //'nama_penelitian_pengabdian',
            //'id_penelitian_pengabdian',
            //'nama_kategori_pencapaian',
            //'id_kategori_capaian_luaran',
            //'id_universitas',
            //'kegiatan_penting_yang_dilakukan',
            //'no_sk_tugas',
            //'tanggal_sk_penugasan',
            //'durasi',
            //'NIY',
            //'sister_id',
            //'updated_at',
            //'created_at',
    ['class' => 'yii\grid\ActionColumn']
];?>    
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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
