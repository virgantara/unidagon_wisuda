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
use kartik\editable\Editable;
use kartik\number\NumberControl;


$list_status_skp = MyHelper::statusSkp();
/* @var $this yii\web\View */
/* @var $model app\models\Skp */

$list_unsur = ArrayHelper::map(\app\models\UnsurUtama::find()->orderBy(['urutan'=>SORT_ASC])->all(),'id','nama');

$this->title = 'Pengisian Form SKP';
$this->params['breadcrumbs'][] = ['label' => 'Skps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$nama_pegawai = '';
// $nama_pejabat_penilai = 

$list_staf = MyHelper::listRoleStaf();

if(in_array($model->pegawaiDinilai->access_role, $list_staf))
{
    $nama_pegawai = $model->pegawaiDinilai->tendik->nama;
}

else
{
    $nama_pegawai = !empty($model->pegawaiDinilai) ? $model->pegawaiDinilai->nama : '-';
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
<?= Html::a('<i class="fa fa-print"></i> Print Rencana SKP', ['print-rencana', 'id' => $model->id], ['class' => 'btn btn-success','target'=>'_blank']) ?>
                <?= Html::a('<i class="fa fa-print"></i> Print Laporan SKP', ['print-formulir', 'id' => $model->id], ['class' => 'btn btn-success','target'=>'_blank']) ?>

                <?= Html::a('<i class="fa fa-search"></i> Realisasi', ['realisasi', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

                
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
                        <th width="40%">: <?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->gelar_depan.' '.$model->pejabatPenilai->dataDiri->nama.' '.$model->pejabatPenilai->dataDiri->gelar_belakang : '-'?></th>
                        <th width="10%">Nama</th>
                        <th width="40%">: <?=$nama_pegawai;?></th>
                    </tr>
                    <tr>
                        <th>NIY</th>
                        <th>: <?=!empty($model->pejabatPenilai) ? $model->pejabat_penilai : '-'?></th>
                        <th>NIY</th>
                        <th>: <?=$model->pegawaiDinilai->NIY;?></th>
                    </tr>
                    
                    <tr>
                        <th>Pangkat</th>
                        <th>: <?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->namaPangkat : '-'?></th>
                        <th>Pangkat</th>
                        <th>: 
                             <?php 
                    if(!in_array($model->pegawaiDinilai->access_role, $list_staf))
                    {
                    ?>
                            <?=$model->pegawaiDinilai->dataDiri->namaPangkat;?>
                            <?php } ?>          
                            </th>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <th>: <?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->namaJabfung : '-'?></th>
                        <th>Jabatan</th>
                        <th>: 
                             <?php 
                    if(!in_array($model->pegawaiDinilai->access_role, $list_staf))
                    {
                    ?>
                            <?=$model->pegawaiDinilai->dataDiri->namaJabfung;?>
                            <?php } ?>          
                            </th>
                    </tr>
                    
                    <tr>
                        <th>Unit Kerja</th>
                        <th>: <?=!empty($model->jabatanPenilai) && !empty($model->jabatanPenilai->unker) ? $model->jabatanPenilai->unker->nama : '-'?></th>
                        <th>Unit Kerja</th>
                        <th>: <?=!empty($model->jabatanPegawai) && !empty($model->jabatanPegawai->unker) ? $model->jabatanPegawai->unker->nama : '-'?></th>
                    </tr>
                    <tr>
                        <th>Status SKP</th>
                        <th>: 
                            <?php 
                            $list_status = ['1'=>'Menunggu persetujuan atasan','2'=>'Disetujui atasan','3'=>'Dikembalikan'];
                            echo $list_status[$model->status_skp];
                             ?>
                        </th>
                        <th></th>
                        <th></th>
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
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
<div class="panel-body ">

 
    <?php
    $gridColumns = [
    [
        'class'=>'kartik\grid\SerialColumn',
        'contentOptions'=>['class'=>'kartik-sheet-style'],
        'width'=>'36px',
        'pageSummary'=>'Total',
        'pageSummaryOptions' => ['colspan' => 3],
        'header'=>'',
        'headerOptions'=>['class'=>'kartik-sheet-style']
    ],
            
            'nama',
            [
                'attribute' => 'komponen_kegiatan_id',
                'contentOptions' => ['width' => '40%'],
                'format' => 'raw',
                'value' => function($data){
                    return !empty($data->komponenKegiatan) ? $data->komponenKegiatan->nama.' - <b>'.$data->komponenKegiatan->subunsur.'</b>' : null;
                }
            ],
            [
                'attribute' => 'capaian',
                'contentOptions' => ['class'=>'text-center'],
                'value' => function($data){
                    return round($data->capaian, 2);
                },
                'pageSummary' => true,
                'pageSummaryOptions' => ['class'=>'text-center'],
                'footer' => function($data){
                    return round($data->sumCapaian, 2);
                },     
            ],
            [
                'attribute' => 'capaian_skp',
                'contentOptions' => ['class'=>'text-center'],   
                'value' => function($data){
                    return round($data->capaian_skp, 2);
                },
                'pageSummary' => true,
                'pageSummaryOptions' => ['class'=>'text-center'],
                'footer' => function($data){
                    return round($data->sumCapaianSkp, 2);
                },     
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action == 'update') {
                        return Url::to(['skp-item/isi','id'=>$model->id]); 
                    }
                },

                 'buttons' => [
                    'update' => function ($url, $model, $key) { 
                         return Html::a('<i class="fa fa-pencil"></i> Isi Capaian', 'javascript:void(0)',[
                            'title'=>'Pengisian Capaian',
                            'class'=>'btn btn-info btn_isi_capaian',
                            'data-item' => $model->id
                        ]);

                    }

                 ],
                // 'visibleButtons' => [
                //     'delete' => function ($data) use ($model) {
                //         return $model->status_skp != 2;
                //     },
                // ]
            ]
];?>    
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => $gridColumns,
        'responsiveWrap' => false,
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
        'pjaxSettings' =>[
            'neverTimeout'=>true,
            'options'=>[
                'id'=>'pjax-container',
            ]
        ], 
        'bordered' => true,
        'striped' => true,
        // 'condensed' => false,
        // 'responsive' => false,
        'hover' => true,
        // 'floatHeader' => true,
        'showPageSummary' => true, //true untuk menjumlahkan nilai di suatu kolom, kebetulan pada contoh tidak ada angka.
        'panel' => [
            'type' => GridView::TYPE_PRIMARY
        ],
    ]); ?>

</div>
        </div>
    </div>

</div>


<?php


yii\bootstrap\Modal::begin([
'headerOptions' => ['id' => 'modalHeader'],
'id' => 'modal',
'size' => 'modal-lg',
'clientOptions' => ['backdrop' => 'static', 'keyboard' => true]
]);
?>

<form action="" id="form-skp-temp">
    <?= Html::hiddenInput('id','',['class'=>'form-control','id'=>'skp_item_id']) ?>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th>Unsur Utama</th>
                    <th colspan="3">: <span id="span_unsur_utama"></span></th>
                </tr>
                <tr>
                    <th>Komponen</th>
                    <th colspan="3">: <span id="span_komponen_kegiatan"></span></th>
                </tr>
                <tr>
                    <th>Nama Kegiatan</th>
                    <th colspan="3">: <span id="span_nama_kegiatan"></span></th>
                    
                </tr>
            </table>
            <table class="table table-bordered">
                <tr>
                    <th width="40%">Item</th>
                    <th width="20%">Satuan</th>
                    <th width="20%" class="text-center">Target</th>
                    <th width="20%">Realisasi</th>
                </tr>
                <tr>
                    <td><b>Kuantitas/Output</b></td>
                    <td><span id="span_target_satuan"></span></td>
                    <td class="text-center"><span id="span_target_qty"></span></td>
                    
                    <td><?= Html::textInput('realisasi_qty','',['class'=>'form-control','id'=>'realisasi_qty','readonly'=>'readonly']) ?></td>
                </tr>
                <tr>
                    <td><b>Kualitas/Mutu</b></td>
                    <td></td>
                    <td class="text-center"><span id="span_target_mutu"></span></td>
                    
                    <td><?= Html::textInput('realisasi_mutu','',['class'=>'form-control','id'=>'realisasi_mutu']) ?></td>
                </tr>
                <tr>
                    <td><b>Waktu</b></td>
                    <td><span id="span_target_waktu_satuan"></span></td>
                    <td class="text-center"><span id="span_target_waktu"></span></td>
                    
                    <td><?= Html::textInput('realisasi_waktu','',['class'=>'form-control','id'=>'realisasi_waktu']) ?></td>
                </tr>
                <tr>
                    <td><b>Biaya</b></td>
                    <td></td>
                    <td class="text-center"><span id="span_target_biaya"></span></td>
                    
                    <td><?= NumberControl::widget([
                            'name' => 'realisasi_biaya',
                            'options' => ['id'=>'realisasi_biaya'],
                            'maskedInputOptions' => [
                                'prefix' => 'Rp ',
                                'groupSeparator' => '.',
                                'radixPoint' => ','
                            ]
                        ]) ?></td>
                </tr>
               
            </table>
            
           
          
            
            <div class="form-group">
                <button id="btn-simpan-skp" class="btn btn-primary btn-block">
                    <i class="fa fa-save"></i> Simpan
                </button>
            </div> 
        </div>
    </div>
    
</form>

<?php
yii\bootstrap\Modal::end();
?>


<?php 

$this->registerJs(' 



$(document).on("click", ".btn_isi_capaian", function(e){
    e.preventDefault();
    var obj = new Object;
    obj.id = $(this).data("item")
    $.ajax({
        url: "'.Url::to(["skp-item/ajax-get"]).'",
        type : "POST",
        async : true,
        data: {
            dataPost : obj
        },
        error : function(e){
            console.log(e.responseText)
        },
        beforeSend: function(){
            Swal.showLoading()
        },
        success: function (data) {
            Swal.close()
            var hasil = $.parseJSON(data)
            $("#skp_item_id").val(hasil.id)
            $("#span_unsur_utama").html(hasil.unsur)
            $("#span_komponen_kegiatan").html(hasil.komponen)
            $("#span_nama_kegiatan").html(hasil.nama)
            $("#span_target_qty").html(hasil.target_qty)
            $("#span_target_satuan").html(hasil.target_satuan)
            $("#span_target_mutu").html(hasil.target_mutu)
            $("#span_target_waktu").html(hasil.target_waktu)
            $("#span_target_waktu_satuan").html(hasil.target_waktu_satuan)
            $("#span_target_biaya").html(hasil.target_biaya)
            $("#realisasi_qty").val(hasil.realisasi_qty)
            $("#realisasi_mutu").val(hasil.realisasi_mutu)
            $("#realisasi_biaya-disp").val(hasil.realisasi_biaya)
            $("#realisasi_waktu").val(hasil.realisasi_waktu)
            $("#modal").modal("show")
            
        }
    })

    
    
});


$(document).on("click", "#btn-simpan-skp", function(e){
    e.preventDefault();
    
    var obj = $("#form-skp-temp").serialize()
    
    $.ajax({
        url: "'.Url::to(["skp-item/ajax-update"]).'",
        type : "POST",
        async : true,
        data: obj,
        error : function(e){
            console.log(e.responseText)
        },
        beforeSend: function(){
            Swal.showLoading()
        },
        success: function (data) {
            Swal.close()
            var hasil = $.parseJSON(data)
            if(hasil.code == 200){
                $("#modal").modal("hide")
                Swal.fire({
                    title: \'Yeay!\',
                    icon: \'success\',
                    text: hasil.message
                });
                
                $.pjax.reload({container: "#pjax-container"});
            }

            else{
                Swal.fire({
                    title: \'Oops!\',
                    icon: \'error\',
                    text: hasil.message
                })
            }
        }
    })
});

', \yii\web\View::POS_READY);

?>