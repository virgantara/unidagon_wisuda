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

$list_status_skp = MyHelper::statusSkp();
/* @var $this yii\web\View */
/* @var $model app\models\Skp */

$list_unsur = ArrayHelper::map(\app\models\UnsurUtama::find()->orderBy(['urutan'=>SORT_ASC])->all(),'id','nama');

$this->title = 'Form SKP Periode '.date('d-m-Y',strtotime($model->periode->tanggal_bkd_awal)).' s/d '.date('d-m-Y',strtotime($model->periode->tanggal_bkd_akhir));
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
 <?php 
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
      echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
    }
    ?>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <?= Html::a('<i class="fa fa-edit"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

                <?= Html::a('<i class="fa fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>

                <?= Html::a('<i class="fa fa-print"></i> Print', ['print-formulir', 'id' => $model->id], ['class' => 'btn btn-success','target'=>'_blank']) ?>

                
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
                            echo Editable::widget([
                                'model' => $model,
                                'attribute' => 'status_skp',
                                'beforeInput' => Html::hiddenInput('editableKey',$model->id),
                                'asPopover' => false,
                                'disabled' => $model->pejabat_penilai != Yii::$app->user->identity->NIY,
                                // 'format' => 'raw',
                                'value' => $model->status_skp,
                                // 'displayValue' => 'oke',
                                'displayValueConfig'=> [
                                    '1' => '<span class="label label-warning">Menunggu persetujuan atasan</span>',
                                    '2' => '<span class="label label-success">Disetujui atasan</span>',
                                    '3' => '<span class="label label-danger">Dikembalikan</span>',
                                ],
                                'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                'data' => ['1'=>'Menunggu persetujuan atasan','2'=>'Disetujui atasan','3'=>'Dikembalikan'],
                                'size'=>'md',
                                'options' => ['class'=>'form-control']
                            ]);
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
            
            'nama',
            [
                'attribute' => 'komponen_kegiatan_id',
                'contentOptions' => ['width' => '25%'],
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
            [
                'attribute' => 'target_biaya',
                'contentOptions' => ['class' => 'text-right'],
                'value' => function($data){
                    return 'Rp '.MyHelper::formatRupiah($data->target_biaya);
                }
            ],
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
                    <td>Unsur Utama*</td>
                    <td colspan="3">
                        <?= Select2::widget([
                            'name' => 'unsur_utama',
                            'data' => $list_unsur,
                            'options'=>['tabindex'=>'0','id'=>'unsur_utama','placeholder'=>Yii::t('app','- Pilih -')],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'dropdownParent' => '#modal'
                            ],
                        ])?>
                        
                    </td>
                </tr>
                <tr>
                    <td>Kegiatan*</td>
                    <td colspan="3">
                        <input type="hidden" id="skp_id" name="skp_id" value="<?=$model->id;?>">
                        <?php 
                        echo DepDrop::widget([
                            'name' => 'komponen_kegiatan_id',
                            'type'=>DepDrop::TYPE_SELECT2,
                            'options'=>['id'=>'komponen_kegiatan_id'],
                            'select2Options'=>[
                                'pluginOptions'=>[
                                    'allowClear'=>true,
                                    'dropdownParent' => '#modal'
                                ]
                            ],
                            'pluginOptions'=>[
                                'depends'=>['unsur_utama'],
                                'initialize' => true,
                                'placeholder'=>'- Pilih -',
                                'url'=>\yii\helpers\Url::to(['/komponen-kegiatan/subkomponen'])
                            ]
                            
                        ]);
                        ?>
                        
                    </td>
                </tr>
                <tr>
                    <td>Nama Kegiatan*</td>
                    <td colspan="3"><?= Html::textInput('nama','',['class'=>'form-control','id'=>'nama']) ?>
                        
                        <?= Html::hiddenInput('target_ak','',['class'=>'form-control','id'=>'target_ak']) ?>
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
   $("#unsur_utama").val("")
   $("#target_ak").val("")
   $("#target_qty").val("")
   $("#target_satuan").val("")
   $("#target_mutu").val("")
   $("#target_waktu").val("")
   $("#target_waktu_satuan").val("")
   $("#target_biaya").val("")
})

$(document).on("change", "#komponen_kegiatan_id", function(e){
    e.preventDefault();
    var obj = new Object
    obj.id = $(this).val()
    $.ajax({
        url: "'.Url::to(["komponen-kegiatan/ajax-get"]).'",
        type : "POST",
        async : true,
        data: {
            dataPost : obj
        },
        error : function(e){
            Swal.close()
            console.log(e.responseText)
        },
        beforeSend: function(){
            Swal.showLoading()
        },
        success: function (data) {
            Swal.close()
            var hasil = $.parseJSON(data)

            $("#target_ak").val(hasil.angka_kredit_pak)
        }
    })
    
});

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