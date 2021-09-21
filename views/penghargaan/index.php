<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\helpers\MyHelper;
$list_tingkat = ArrayHelper::map(\app\models\Tingkat::find()->all(),'id','nama');

/* @var $this yii\web\View */
/* @var $searchModel app\models\PenghargaanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Penghargaans';
$this->params['breadcrumbs'][] = $this->title;


$listKegiatan1 = MyHelper::convertKategoriKegiatan('1408');
// print_r($listKegiatan1);exit;
$listKegiatan2 = MyHelper::convertKategoriKegiatan('1410');

$listKegiatan = array_merge($listKegiatan1, $listKegiatan2);

$list_tingkat = ArrayHelper::map(\app\models\TingkatPenghargaan::find()->all(),'id','nama');
$list_jenis = ArrayHelper::map(\app\models\JenisPenghargaan::find()->all(),'id','nama');
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
        <?= Html::a('Create Penghargaan', ['create'], ['class' => 'btn btn-success']) ?>
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

            'bentuk',
            'pemberi',
            [
                'attribute' => 'kategori_kegiatan_id',
                'filter' => $listKegiatan,
                'contentOptions' => ['width'=>'15%'],
                'value' => function($data){
                    return !empty($data->kategoriKegiatan) ? $data->kategoriKegiatan->nama : '-';
                }
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'id_tingkat_penghargaan',
                'filter' => $list_tingkat,
                'refreshGrid' => true,
                'value' => function($data){
                    return !empty($data->tingkatPenghargaan) ? $data->tingkatPenghargaan->nama : '-';
                },
                'editableOptions' => [
                    'data' => $list_tingkat,
                    'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                    'asPopover' => false
                ],
                
            ],
            [
                'attribute' => 'id_jenis_penghargaan',
                'filter' => $list_jenis,
                'contentOptions' => ['width'=>'15%'],
                'value' => function($data){
                    return !empty($data->jenisPenghargaan) ? $data->jenisPenghargaan->nama : '-';
                }
            ],
            'tahun',
            'tanggal',
            
            
            
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

