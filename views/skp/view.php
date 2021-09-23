<?php
use app\helpers\MyHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\grid\GridView;

use yii\jui\AutoComplete;
use yii\web\JsExpression;

$list_status_skp = MyHelper::statusSkp();
/* @var $this yii\web\View */
/* @var $model app\models\Skp */

$this->title = 'Form SKP Periode '.date('d-m-Y',strtotime($model->periode->tanggal_bkd_awal)).' s/d '.date('d-m-Y',strtotime($model->periode->tanggal_bkd_akhir));
$this->params['breadcrumbs'][] = ['label' => 'Skps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
  .ui-autocomplete { z-index:2147483647; }

  /*.modal-dialog{
      top: 50%;
      margin-top: -250px; 
  }*/

</style>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
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
                        <th width="40%">: <?=$model->pegawaiDinilai->dataDiri->gelar_depan;?> <?=$model->pegawaiDinilai->dataDiri->nama;?> <?=$model->pegawaiDinilai->dataDiri->gelar_belakang;?></th>
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
                        <th>: <?=$model->pegawaiDinilai->dataDiri->namaPangkat;?></th>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <th>: <?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->namaJabfung : '-'?></th>
                        <th>Jabatan</th>
                        <th>: <?=$model->pegawaiDinilai->dataDiri->namaJabfung;?></th>
                    </tr>
                    <tr>
                        <th>Unit Kerja</th>
                        <th>: <?=!empty($model->jabatanPenilai) && !empty($model->jabatanPenilai->unker) ? $model->jabatanPenilai->unker->nama : '-'?></th>
                        <th>Unit Kerja</th>
                        <th>: <?=!empty($model->jabatanPegawai) && !empty($model->jabatanPegawai->unker) ? $model->jabatanPegawai->unker->nama : '-'?></th>
                    </tr>
                </tbody>
            </table>

<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            // 'pejabat_penilai',
            // 'pegawai_dinilai',
            // 'periode_id',
            [
                'attribute' => 'status_skp',
                'format' => 'raw',
                'value' => function($data) use ($list_status_skp){
                    return '<span class="label label-'.$list_status_skp[$data->status_skp]['label'].'">'.$list_status_skp[$data->status_skp]['nama'].'</span>';
                }
            ],
            // 'updated_at',
            // 'created_at',
        ],
    ]) ?>

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

    <p>
        <?= Html::a('<i class="fa fa-plus"></i> Item', '#', ['class' => 'btn btn-success','id'=>'btn-add']) ?>
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
                'attribute' => 'komponen_kegiatan_id',
                'contentOptions' => ['width' => '30%'],
                'value' => function($data){
                    return !empty($data->komponenKegiatan) ? $data->komponenKegiatan->nama.' - '.$data->komponenKegiatan->subunsur : null;
                }
            ],
            'target_ak',
            'target_qty',
            'target_satuan',
            'target_mutu',
            'target_waktu',
            //'target_waktu_satuan',
            'target_biaya',
            //'realisasi_ak',
            //'realisasi_qty',
            //'realisasi_satuan',
            //'realisasi_mutu',
            //'realisasi_waktu',
            //'realisasi_waktu_satuan',
            //'realisasi_biaya',
            //'capaian',
            //'capaian_skp',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action == 'delete')
                    {
                        return Url::to(['skp-item/delete','id'=>$model->id]); 
                    }
                }
            ]
];?>    
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
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


yii\bootstrap\Modal::begin([
'headerOptions' => ['id' => 'modalHeader'],
'id' => 'modal',
'size' => 'modal-lg',
'clientOptions' => ['backdrop' => 'static', 'keyboard' => true]
]);
?>

<form action="" id="form-skp-temp">
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <td>Kegiatan*</td>
                    <td colspan="3">
                        <input type="hidden" id="skp_id" name="skp_id" value="<?=$model->id;?>">
                        <input type="hidden" id="komponen_kegiatan_id" name="komponen_kegiatan_id">
                        <input type="hidden" id="target_ak" name="target_ak">
                        <?= Html::textInput('nama_komponen_kegiatan','',['class'=>'form-control','id'=>'nama_komponen_kegiatan','placeholder'=>'Ketik Komponen Kegiatan']) ?>
                        <?php 
                        AutoComplete::widget([
                            'name' => 'nama_komponen_kegiatan',
                            'id' => 'nama_komponen_kegiatan',
                            'clientOptions' => [
                            'source' => Url::to(['komponen-kegiatan/ajax-cari']),
                            'autoFill'=>true,
                            'minLength'=>'1',
                            'select' => new JsExpression("function( event, ui ) {
                                $('#komponen_kegiatan_id').val(ui.item.id);
                                $('#target_ak').val(ui.item.ak);
                                
                             }")],
                            'options' => [
                                // 'size' => '40'
                            ]
                         ]); ?>
                    </td>
                </tr>
                <tr>
                    <td>Kuantitas/Output*</td>
                    <td><?= Html::textInput('target_qty','',['class'=>'form-control']) ?></td>
                    <td>Satuan</td>
                    <td><?= Html::textInput('target_satuan','',['class'=>'form-control']) ?></td>
                </tr>
                <tr>
                    <td>Kualitas/Mutu*</td>
                    <td colspan="3"><?= Html::textInput('target_mutu','',['class'=>'form-control']) ?></td>
                </tr>
                <tr>
                    <td>Waktu*</td>
                    <td><?= Html::textInput('target_waktu','',['class'=>'form-control']) ?></td>
                    <td>Satuan</td>
                    <td><?= Html::textInput('target_waktu_satuan','',['class'=>'form-control']) ?></td>
                </tr>
                <tr>
                    <td>Biaya*</td>
                    <td colspan="3"><?= Html::textInput('target_biaya','',['type'=>'number','class'=>'form-control']) ?></td>
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

$("#modal").on("shown.bs.modal", function (e) {
   
})

$(document).on("click", "#btn-add", function(e){
    e.preventDefault();
    $("#modal").modal("show")
    
});


$(document).on("click", "#btn-simpan-skp", function(e){
    e.preventDefault();
    
    var obj = $("#form-skp-temp").serialize()
    
    $.ajax({
        url: "'.Url::to(["skp-item/ajax-add"]).'",
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