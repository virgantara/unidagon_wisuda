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


                <?= Html::a('<i class="fa fa-print"></i> Print', ['print', 'id' => $model->id], ['class' => 'btn btn-success','target'=>'_blank']) ?>
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
                    <tr>
                        <th>Status SKP</th>
                        <th>: 
                            <?php 
                            echo '<span class="label label-'.$list_status_skp[$model->status_skp]['label'].'">'.$list_status_skp[$model->status_skp]['nama'].'</span>';
                            
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

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center" width="3%">No</th>
                            <th rowspan="2" class="text-center" width="32%">Kegiatan</th>
                            <th rowspan="2" class="text-center" width="5%">AK</th>
                            <th colspan="4" class="text-center" width="20%">Target</th>
                            <th rowspan="2" class="text-center" width="5%">AK</th>
                            <th colspan="4" class="text-center" width="20%">Realisasi</th>
                            <th rowspan="2" class="text-center" width="5%">Penghitungan</th>
                            <th rowspan="2" class="text-center" width="5%">Nilai Capaian SKP</th>
                        </tr>
                        <tr>
                            <th width="5%">Kuant/Output</th>
                            <th width="5%">Kual/Mutu</th>
                            <th width="5%">Waktu</th>
                            <th width="5%">Biaya</th>
                            <th width="5%">Kuant/Output</th>
                            <th width="5%">Kual/Mutu</th>
                            <th width="5%">Waktu</th>
                            <th width="5%">Biaya</th>
                        </tr>
                        <tr>
                            <?php 
                            for($i=1;$i<=14;$i++)
                                echo '<th class="text-center" style="font-size:12px">'.$i.'</th>';
                             ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $capaian_total = 0;
                        $counter=0;
                        foreach($model->skpItems as $q => $item)
                        {
                            $counter++;

                            $item->hitungSkp();
                            $penghitungan = $item->capaian;
                            $capaian_skp = $item->capaian_skp;
                            $capaian_total += $capaian_skp;


                         ?>
                        
                        <tr>
                            <td><?=$q+1;?></td>
                            <td><?=$item->komponenKegiatan->nama.' - '.$item->komponenKegiatan->subunsur;?></td>
                            <td class="text-center"><?=$item->target_ak;?></td>
                            <td class="text-center"><?=$item->target_qty;?> <?=$item->target_satuan;?></td>
                            <td class="text-center"><?=$item->target_mutu;?></td>
                            <td class="text-center"><?=$item->target_waktu;?> <?=$item->target_waktu_satuan;?></td>
                            
                            <td class="text-right"><?=MyHelper::formatRupiah($item->target_biaya);?></td>
                        
                            <td class="text-center">
                                <?php 
                                echo Editable::widget([
                                    'name' => 'realisasi_ak',
                                    'beforeInput' => Html::hiddenInput('editableKey',$item->id),
                                    'asPopover' => false,
                                    // 'format' => 'raw',
                                    'value' => $item->realisasi_ak,
                                    // 'displayValue' => 'oke',
                                    'size'=>'md',
                                    'options' => ['class'=>'form-control']
                                ]);
                                 ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                echo Editable::widget([
                                    'name' => 'realisasi_qty',
                                    'beforeInput' => Html::hiddenInput('editableKey',$item->id),
                                    'asPopover' => false,
                                    // 'format' => 'raw',
                                    'value' => $item->realisasi_qty,
                                    // 'displayValue' => 'oke',
                                    'size'=>'md',
                                    'options' => ['class'=>'form-control']
                                ]);
                                 ?> <?=$item->realisasi_satuan;?>
                                    
                            </td>
                            <td class="text-center">
                                <?php 
                                echo Editable::widget([
                                    'name' => 'realisasi_mutu',
                                    'beforeInput' => Html::hiddenInput('editableKey',$item->id),
                                    'asPopover' => false,
                                    // 'format' => 'raw',
                                    'value' => $item->realisasi_mutu,
                                    // 'displayValue' => 'oke',
                                    'size'=>'md',
                                    'options' => ['class'=>'form-control']
                                ]);
                                 ?> 
                            </td>
                            <td class="text-center">
                                <?php 
                                echo Editable::widget([
                                    'name' => 'realisasi_waktu',
                                    'beforeInput' => Html::hiddenInput('editableKey',$item->id),
                                    'asPopover' => false,
                                    // 'format' => 'raw',
                                    'value' => $item->realisasi_waktu,
                                    // 'displayValue' => 'oke',
                                    'size'=>'md',
                                    'options' => ['class'=>'form-control']
                                ]);
                                 ?> 
                                <?=$item->realisasi_waktu_satuan;?></td>
                            
                            <td class="text-right">
                                <?php 
                                echo Editable::widget([
                                    'name' => 'realisasi_biaya',
                                    'beforeInput' => Html::hiddenInput('editableKey',$item->id),
                                    'asPopover' => false,
                                    // 'format' => 'raw',
                                    'value' => MyHelper::formatRupiah($item->realisasi_biaya),
                                    // 'displayValue' => 'oke',
                                    'size'=>'md',
                                    'options' => ['class'=>'form-control']
                                ]);
                                 ?>

                            </td>
                            <td class="text-center"><?=round($penghitungan,2);?></td>
                            <td class="text-center"><?=round($capaian_skp,2);?></td>
                            
                        </tr>
                    <?php 

                    } 

                    $avg_capaian_skp = $capaian_total / $counter;
                    $kesimpulan = '';

                    if($avg_capaian_skp <= 50)
                        $kesimpulan = 'Buruk';
                    else if($avg_capaian_skp <= 60)
                        $kesimpulan = 'Sedang';
                    else if($avg_capaian_skp <= 75)
                        $kesimpulan = 'Cukup';
                    else if($avg_capaian_skp <= 90.99)
                        $kesimpulan = 'Baik';
                    else
                        $kesimpulan = 'Baik Sekali';
                    ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="13" class="text-right">Nilai Capaian SKP</td>
                            <td class="text-center">
                                <?=round($avg_capaian_skp,2);?>
                                (<b><?=$kesimpulan;?></b>)
                            </td>
                            
                        </tr>
                    </tfoot>
                </table>

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
                            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
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
                    <td>Angka Kredit*</td>
                    <td colspan="3"><?= Html::textInput('target_ak','',['class'=>'form-control','id'=>'target_ak']) ?></td>
                    
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