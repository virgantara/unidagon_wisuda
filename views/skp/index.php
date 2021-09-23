<?php
use app\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SkpSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Skps';
$this->params['breadcrumbs'][] = $this->title;

$list_status_skp = MyHelper::statusSkp();

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
        <?= Html::a('Create Skp', ['create'], ['class' => 'btn btn-success']) ?>
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
            [
                'attribute' => 'pejabat_penilai',
                'value' => function($data){
                    return !empty($data->pejabatPenilai) ? $data->pejabatPenilai->dataDiri->gelar_depan.' '.$data->pejabatPenilai->dataDiri->nama.' '.$data->pejabatPenilai->dataDiri->gelar_belakang : null;
                }
            ],
            [
                'attribute' => 'pegawai_dinilai',
                'value' => function($data){
                    return !empty($data->pegawaiDinilai) ? $data->pegawaiDinilai->dataDiri->gelar_depan.' '.$data->pegawaiDinilai->dataDiri->nama.' '.$data->pegawaiDinilai->dataDiri->gelar_belakang : null;
                }
            ],
            'periode_id',
            [
                'attribute' => 'status_skp',
                'format' => 'raw',
                'filter' => ['1'=>'Diajukan','2'=>'Disetujui','3'=>'Ditolak'],
                'value' => function($data) use ($list_status_skp){
                    return '<span class="label label-'.$list_status_skp[$data->status_skp]['label'].'">'.$list_status_skp[$data->status_skp]['nama'].'</span>';
                }
            ],
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

