<?php
use app\helpers\MyHelper;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use kartik\depdrop\DepDrop;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use kartik\select2\Select2;

$this->title = 'Riwayat SKP';
$this->params['breadcrumbs'][] = ['label' => 'SKP', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;

$list_status_skp = MyHelper::statusSkp();
/* @var $this yii\web\View */
/* @var $model app\models\Skp */

$nama_pegawai = '';
// $nama_pejabat_penilai = 

$list_staf = MyHelper::listRoleStaf();

if(in_array(Yii::$app->user->identity->access_role, $list_staf))
{
    $nama_pegawai = $pegawaiDinilai->tendik->nama;
}

else
{
    $nama_pegawai = $pegawaiDinilai->dataDiri->gelar_depan.' '.$pegawaiDinilai->dataDiri->nama.' '.$pegawaiDinilai->dataDiri->gelar_belakang;
}


?>
<style type="text/css">
  .ui-autocomplete { z-index:2147483647; }

  .select2-selection--single {
  height: 100% !important;
}
.select2-selection__rendered{
  word-wrap: break-word !important;
  text-overflow: inherit !important;
  white-space: normal !important;
}
</style>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
              
            </div>

            <div class="panel-body ">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th colspan="3"><h3>I. Pejabat Penilai</h3></th>
                        <th colspan="3"><h3>II. Pegawai Dinilai</h3></th>
                    </tr>
                    <tr>
                        <th width="10%">Nama</th>
                        <th width="40%">: <?=!empty($pejabatPenilai->dataDiri) ? $pejabatPenilai->dataDiri->gelar_depan.' '.$pejabatPenilai->dataDiri->nama.' '.$pejabatPenilai->dataDiri->gelar_belakang : '-'?></th>
                        <th width="10%">Nama</th>
                        <th width="40%">: <?=$nama_pegawai;?></th>
                    </tr>
                    <tr>
                        <th>NIY</th>
                        <th>: <?=!empty($pejabatPenilai) ? $pejabatPenilai->NIY : '-'?></th>
                        <th>NIY</th>
                        <th>: <?=$pegawaiDinilai->NIY;?></th>
                    </tr>
                    
                    <tr>
                        <th>Pangkat</th>
                        <th>: <?=!empty($pejabatPenilai->dataDiri) ? $pejabatPenilai->dataDiri->namaPangkat : '-'?></th>
                        <th>Pangkat</th>
                        <th>: 
                             <?php 
                            if(!in_array(Yii::$app->user->identity->access_role, $list_staf))
                            {
                            ?>
                            <?=$pegawaiDinilai->dataDiri->namaPangkat;?>
                        <?php } ?>          
                            </th>
                    </tr>

                    <tr>
                        <th>Jabatan</th>
                        <th>: <?=!empty($pejabatPenilai->dataDiri) ? $pejabatPenilai->dataDiri->namaJabfung : '-'?></th>
                        <th>Jabatan</th>
                        <th>: 
                            <?php 
                            if(!in_array(Yii::$app->user->identity->access_role, $list_staf))
                            {
                            ?>
                            <?=$pegawaiDinilai->dataDiri->namaJabfung;?>
                              <?php } ?>  
                        </th>
                    </tr>
                    <tr>
                        <th>Unit Kerja</th>
                        <th>: <?=!empty($jabatanPenilai) && !empty($jabatanPenilai->unker) ? $jabatanPenilai->unker->nama : '-'?></th>
                        <th>Unit Kerja</th>
                        <th>: <?=!empty($jabatanPegawai) && !empty($jabatanPegawai->unker) ? $jabatanPegawai->unker->nama : '-'?></th>
                    </tr>
                </tbody>
            </table>

            </div>
        </div>

    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Form SKP</h3>
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
                'attribute' => 'pejabat_penilai',
                'value' => function($data){
                    return !empty($data->pejabatPenilai) ? $data->pejabatPenilai->dataDiri->gelar_depan.' '.$data->pejabatPenilai->dataDiri->nama.' '.$data->pejabatPenilai->dataDiri->gelar_belakang : null;
                }
            ],
            [
                'attribute' => 'pegawai_dinilai',
                'value' => function($data) use ($list_staf){

                    $nama_pegawai = '';
                    
                    if(in_array($data->pegawaiDinilai->access_role, $list_staf))
                    {
                        $nama_pegawai = $data->pegawaiDinilai->tendik->nama;
                    }

                    else
                    {
                        $nama_pegawai = !empty($data->pegawaiDinilai) ? $data->pegawaiDinilai->dataDiri->gelar_depan.' '.$data->pegawaiDinilai->dataDiri->nama.' '.$data->pegawaiDinilai->dataDiri->gelar_belakang : null;
                    }
                    return $nama_pegawai;
                }
            ],
            [
                'attribute' => 'periode_id',
                'value' => function($data){
                    return date('d-m-Y',strtotime($data->periode->tanggal_bkd_awal)).' s/d '.date('d-m-Y',strtotime($data->periode->tanggal_bkd_akhir));
                }
            ],
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
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view}'
    ]
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

<?php 

$this->registerJs(' 


});

', \yii\web\View::POS_READY);

?>