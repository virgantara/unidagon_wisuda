<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PenunjangLainSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Penunjang Lains';
$this->params['breadcrumbs'][] = $this->title;

$listKegiatan1 = \app\helpers\MyHelper::convertKategoriKegiatan('1401');
$listKegiatan2 = \app\helpers\MyHelper::convertKategoriKegiatan('1402');
$listKegiatan3 = \app\helpers\MyHelper::convertKategoriKegiatan('1404');
$listKegiatan4 = \app\helpers\MyHelper::convertKategoriKegiatan('1405');
$listKegiatan5 = \app\helpers\MyHelper::convertKategoriKegiatan('1407');
$listKegiatan6 = \app\helpers\MyHelper::convertKategoriKegiatan('1411');
$listKegiatan = array_merge($listKegiatan1, $listKegiatan2, $listKegiatan3, $listKegiatan4, $listKegiatan5, $listKegiatan6);

$list_jenis = \app\models\JenisPanitia::find()->all();
$listJenisPanitia = ArrayHelper::map($list_jenis,'id','nama');
$list_tingkat = ArrayHelper::map(\app\models\Tingkat::find()->all(),'id','nama');

$query = \app\models\KomponenKegiatan::find();
$query->alias('p');
$query->select(['p.nama']);
$query->joinWith(['unsur as u']);
$query->where([
  'u.kode' => 'PENUNJANG'
]);
$query->groupBy(['p.nama']);
$query->orderBy(['p.nama'=>SORT_ASC]);

$listKomponen = $query->all();
// $listKomponen = ArrayHelper::map($listKomponen,'id',function($data){
//     return $data->subunsur;
// });
$listKomponenKegiatan = [];

foreach($listKomponen as $k)
{
    $list = \app\models\KomponenKegiatan::find()->where(['nama'=>$k->nama])->all();
   
    $tmp = [];
    foreach($list as $item)
    {
        $tmp[$item->id] = $item->subunsur.' - AK: '.$item->angka_kredit;
    }

    $listKomponenKegiatan[$k->nama] = $tmp;
}
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
        <?= Html::a('Create Penunjang Lain', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'kategori_kegiatan_id',
                'filter' => $listKegiatan,
                'contentOptions' => ['width'=>'15%'],
                'value' => function($data){
                    return !empty($data->kategoriKegiatan) ? $data->kategoriKegiatan->nama : '-';
                }
            ],
            
           
            [
                'attribute' => 'komponen_kegiatan_id',
                'contentOptions' => ['width'=>'15%'],
                'filter' => $listKomponenKegiatan,
                'value' => function($data){
                    return !empty($data->komponenKegiatan) ? $data->komponenKegiatan->nama : '-';
                }
            ],
            
            [
                'attribute' => 'jenis_panitia_id',
                'contentOptions' => ['width'=>'15%'],
                'filter' => $listJenisPanitia,
                'value' => function($data){
                    return !empty($data->jenisPanitia) ? $data->jenisPanitia->nama : '-';
                }
            ],
            [
                'attribute' => 'tingkat_id',
                'contentOptions' => ['width'=>'10%'],
                'filter' => $list_tingkat,
                'value' => function($data){
                    return !empty($data->tingkat) ? $data->tingkat->nama : '-';
                }
            ],
            'nama_kegiatan',
            'instansi',
            'no_sk_tugas',
            'tanggal_mulai:date',
            'tanggal_selesai:date',
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

