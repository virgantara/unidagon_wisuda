<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrasiIlmiahSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orasi Ilmiah';
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
        <?= Html::a('Create Orasi Ilmiah', ['create'], ['class' => 'btn btn-success']) ?>
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
            // 'NIY',
            [
                'attribute' => 'kategori_kegiatan_id',
                'value' => function($data){
                    return !empty($data->kategoriKegiatan) ? $data->kategoriKegiatan->nama :null;
                }
            ],
            [
                'attribute' => 'tingkat_pertemuan_id',
                'filter' => $list_tingkat,
                'value' => function($data){
                    return !empty($data->tingkatPertemuan) ? $data->tingkatPertemuan->nama :null;
                }
            ],
            'nama_kategori_pencapaian',
            //'id_kategori_capaian_luaran',
            //'kategori_kegiatan_id',
            'judul_buku_makalah',
            'nama_pertemuan_ilmiah',
            'penyelenggara_kegiatan',
            'tanggal_pelaksanaan',
            //'id_kategori_pembicara',
            'no_sk_tugas',
            'tanggal_sk_penugasan',
            'bahasa',
            [
                'attribute'=>'file_path',
                'format'=>'raw',
                'value' => function($data){
                    if(!empty($data->file_path)){
                        return Html::a('<i class="fa fa-download"></i> View', $data->file_path,['class' => 'btn btn-info','target'=>'_blank']);
                    }
                    else
                    {
                        return "<p class='btn btn-danger' align='center'>No File</p>";
                    }
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

