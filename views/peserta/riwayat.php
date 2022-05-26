<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PesertaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Peserta WISUDA';
$this->params['breadcrumbs'][] = $this->title;

$list_periode = \app\models\Periode::find()->orderBy(['tahun'=>SORT_DESC])->all();

setlocale(LC_ALL, 'id_ID', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');


?>

<h3><?= Html::encode($this->title) ?></h3>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Periode Pendaftaran </h3>
            </div>
<div class="panel-body ">

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
                'attribute' => 'periode_id',
                'filter' => ArrayHelper::map($list_periode,'id_periode','nama_periode'),
                'value' => function($data){
                    return !empty($data->periode) ? $data->periode->nama_periode : null;
                }
            ],
            'nim',
            'nama_lengkap',
            'fakultas',
            'prodi',
            'tempat_lahir',
            [
                'attribute' => 'tanggal_lahir',
                'value' => function($data){
                    return strftime('%d %B %Y',strtotime($data->tanggal_lahir));
                }
            ],
            [
                'attribute'=>'jenis_kelamin',
                'filter' => ['L'=>'Laki-laki','P'=>'Perempuan'],
                // 'value' => function($)
            ],
            //'status_warga',
            //'warga_negara',
            'alamat:ntext',
            'no_telp',
            //'nama_ayah',
            //'pekerjaan_ayah',
            'nama_ibu',
            //'pekerjaan_ibu',
            //'pas_photo',
            //'ijazah',
            //'akta_kelahiran',
            //'kwitansi_jilid',
            //'surat_bebas_pinjaman',
            //'resume_skripsi',
            //'surat_bebas_tunggakan',
            //'transkrip',
            //'skl_tahfidz',
            //'kwitansi_wisuda',
            //'tanda_keluar_asrama',
            //'surat_jalan',
            //'skripsi',
            //'abstrak',
            'kode_pendaftaran',
            //'kampus',
            //'status_validasi',
            //'kmi',
            //'bukti_revisi_bahasa',
            //'bukti_layouter',
            //'bukti_perpus',
            'created',
            'status_validasi',
            //'drive_path:ntext',
    // ['class' => 'yii\grid\ActionColumn']
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
                    ['content'=> $this->title, 'options'=>['colspan'=>15, 'class'=>'text-center warning']], //cuma satu 
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

