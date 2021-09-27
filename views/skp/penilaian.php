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


$nama_pegawai = '';
// $nama_pejabat_penilai = 

$list_staf = MyHelper::listRoleStaf();

// if(in_array($pegawaiDinilai->access_role, $list_staf))
// {
//     $nama_pegawai = $pegawaiDinilai->tendik->nama;
// }

// else
// {
//     $nama_pegawai = !empty($pegawaiDinilai) ? $pegawaiDinilai->nama : '-';
// }

$this->title = 'Penilaian Perilaku Pegawai Periode '.date('d-m-Y',strtotime($model->periode->tanggal_bkd_awal)).' s/d '.date('d-m-Y',strtotime($model->periode->tanggal_bkd_akhir));
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

                <h3 class="panel-title"><?=$this->title;?></h3>
            </div>

            <div class="panel-body ">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th colspan="4"><h3>I. Pegawai Dinilai</h3></th>
                    </tr>
                    <tr>
                       
                        <th width="20%">Nama</th>
                        <th width="80%" colspan="3">: <?=$pegawaiDinilai->nama;?></th>
                    </tr>
                    <tr>
                        <th>NIY</th>
                        <th colspan="3">: <?=$pegawaiDinilai->NIY;?></th>
                    </tr>
                    <tr>
                      
                        <th>Pangkat</th>
                        <th colspan="3">: 
                            <?php 
                            if(!in_array($pegawaiDinilai->access_role, $list_staf))
                            {
                             ?>
                            
                            <?=$pegawaiDinilai->dataDiri->namaPangkat;?>
                            <?php } ?>    
                            </th>
                    </tr>
                    <tr>
                       
                        <th>Jabatan</th>
                        <th colspan="3">:
                         <?php 
                            if(!in_array($pegawaiDinilai->access_role, $list_staf))
                            {
                             ?> 
                            <?=$pegawaiDinilai->dataDiri->namaJabfung;?>
                               <?php } ?> 
                            </th>
                    </tr>
                    <tr>
               
                        <th>Unit Kerja</th>
                        <th colspan="3">: <?=!empty($model->jabatanPegawai) && !empty($model->jabatanPegawai->unker) ? $model->jabatanPegawai->unker->nama : '-'?></th>
                    </tr>
                    <tr>
                        <th colspan="4"><h3>II. Pejabat Penilai</h3></th>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <th colspan="3">: <?=!empty($pejabatPenilai->dataDiri) ? $pejabatPenilai->dataDiri->gelar_depan.' '.$pejabatPenilai->dataDiri->nama.' '.$pejabatPenilai->dataDiri->gelar_belakang : '-'?></th>
                       
                    </tr>
                    <tr>
                        <th>NIY</th>
                        <th colspan="3">: <?=!empty($pejabatPenilai) ? $pejabatPenilai->NIY : '-'?></th>
                   
                    </tr>
                    <tr>
                        <th>Pangkat</th>
                        <th colspan="3">: <?=!empty($pejabatPenilai->dataDiri) ? $pejabatPenilai->dataDiri->namaPangkat : '-'?></th>
                   
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <th colspan="3">: <?=!empty($pejabatPenilai->dataDiri) ? $pejabatPenilai->dataDiri->namaJabfung : '-'?></th>
                   
                    </tr>
                    <tr>
                        <th>Unit Kerja</th>
                        <th colspan="3">: <?=!empty($model->jabatanPenilai) && !empty($model->jabatanPenilai->unker) ? $model->jabatanPenilai->unker->nama : '-'?></th>
                    </tr>
                    <tr>
                        <th colspan="4"><h3>III. Atasan Pejabat Penilai</h3></th>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <th colspan="3">: <?=!empty($atasanPejabatPenilai->dataDiri) ? $atasanPejabatPenilai->dataDiri->gelar_depan.' '.$atasanPejabatPenilai->dataDiri->nama.' '.$atasanPejabatPenilai->dataDiri->gelar_belakang : '-'?></th>
                       
                    </tr>
                    <tr>
                        <th>NIY</th>
                        <th colspan="3">: <?=!empty($atasanPejabatPenilai) ? $atasanPejabatPenilai->NIY : '-'?></th>
                   
                    </tr>
                    <tr>
                        <th>Pangkat</th>
                        <th colspan="3">: <?=!empty($atasanPejabatPenilai->dataDiri) ? $atasanPejabatPenilai->dataDiri->namaPangkat : '-'?></th>
                   
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <th colspan="3">: <?=!empty($atasanPejabatPenilai->dataDiri) ? $atasanPejabatPenilai->dataDiri->namaJabfung : '-'?></th>
                   
                    </tr>
                    <tr>
                        <th>Unit Kerja</th>
                        <th colspan="3">: <?=!empty($model->jabatanAtasanPenilai) && !empty($model->jabatanAtasanPenilai->unker) ? $model->jabatanAtasanPenilai->unker->nama : '-'?></th>
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


                UNSUR YANG DINILAI
            </div>

            <div class="panel-body ">
            <table class="table table-hover table-striped table-bordered">
                <tbody>
            
                    <tr>
                        <th width="20%">a. Sasaran Kerja Pegawai (SKP)</th>
                        <th width="20%"></th>
                        <th width="20%" class="text-center"><?=round($avg_capaian_skp,2);?></th>
                        <th width="20%" class="text-center">x 60 %</th>
                        <th width="20%" class="text-center"><?=round($bobot_capaian_skp,2);?></th>
                    </tr>
                    <tr>
                        <th>b. Perilaku Kerja</th>
                        <th>1. Orientasi Pelayanan</th>
                        <th class="text-center">
                             <?php 
                            echo Editable::widget([
                                'name' => 'orientasi',
                                'beforeInput' => Html::hiddenInput('editableKey',$model->id),
                                'asPopover' => false,
                                // 'format' => 'raw',
                                'value' => !empty($skpPerilaku) ? $skpPerilaku->orientasi : null,
                                // 'displayValue' => 'oke',
                                'size'=>'md',
                                'options' => ['class'=>'form-control'],
                                'pluginEvents' => [
                                    "editableSuccess" => "function(event, val, form, data) { 
                                        location.reload();
                                    }",
                                ],
                            ]);
                             ?>
                        </th>
                        <th class="text-center">
                        <?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->orientasi : 0)?>
                             
                         </th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>2. Integritas</th>
                        <th class="text-center">
                          
                            <?php 
                            echo Editable::widget([
                                'name' => 'integritas',
                                'beforeInput' => Html::hiddenInput('editableKey',$model->id),
                                'asPopover' => false,
                                // 'format' => 'raw',
                                'value' => !empty($skpPerilaku) ? $skpPerilaku->integritas : null,
                                // 'displayValue' => 'oke',
                                'size'=>'md',
                                'options' => ['class'=>'form-control'],
                                'pluginEvents' => [
                                    "editableSuccess" => "function(event, val, form, data) { 
                                        location.reload();
                                    }",
                                ],
                            ]);
                             ?>
                        </th>
                        <th class="text-center"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->integritas : 0)?></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>3. Komitmen</th>
                        <th class="text-center">
                             <?php 
                            echo Editable::widget([
                                'name' => 'komitmen',
                                'beforeInput' => Html::hiddenInput('editableKey',$model->id),
                                'asPopover' => false,
                                // 'format' => 'raw',
                                'value' => !empty($skpPerilaku) ? $skpPerilaku->komitmen : null,
                                // 'displayValue' => 'oke',
                                'size'=>'md',
                                'options' => ['class'=>'form-control'],
                                'pluginEvents' => [
                                    "editableSuccess" => "function(event, val, form, data) { 
                                        location.reload();
                                    }",
                                ],
                            ]);
                             ?>
                        </th>
                        <th class="text-center"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->komitmen : 0)?></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>4. Disiplin</th>
                        <th class="text-center"><?php 
                            echo Editable::widget([
                                'name' => 'disiplin',
                                'beforeInput' => Html::hiddenInput('editableKey',$model->id),
                                'asPopover' => false,
                                // 'format' => 'raw',
                                'value' => !empty($skpPerilaku) ? $skpPerilaku->disiplin : null,
                                // 'displayValue' => 'oke',
                                'size'=>'md',
                                'options' => ['class'=>'form-control'],
                                'pluginEvents' => [
                                    "editableSuccess" => "function(event, val, form, data) { 
                                        location.reload();
                                    }",
                                ],
                            ]);
                             ?></th>
                        <th class="text-center"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->disiplin : 0)?></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>5. Kerjasama</th>
                        <th class="text-center"><?php 
                            echo Editable::widget([
                                'name' => 'kerjasama',
                                'beforeInput' => Html::hiddenInput('editableKey',$model->id),
                                'asPopover' => false,
                                // 'format' => 'raw',
                                'value' => !empty($skpPerilaku) ? $skpPerilaku->kerjasama : null,
                                // 'displayValue' => 'oke',
                                'size'=>'md',
                                'options' => ['class'=>'form-control'],
                                'pluginEvents' => [
                                    "editableSuccess" => "function(event, val, form, data) { 
                                        location.reload();
                                    }",
                                ],
                            ]);
                             ?></th>
                        <th class="text-center"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->kerjasama : 0)?></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>6. Kepemimpinan</th>
                        <th class="text-center"><?php 
                            echo Editable::widget([
                                'name' => 'kepemimpinan',
                                'beforeInput' => Html::hiddenInput('editableKey',$model->id),
                                'asPopover' => false,
                                // 'format' => 'raw',
                                'value' => !empty($skpPerilaku) ? $skpPerilaku->kepemimpinan : null,
                                // 'displayValue' => 'oke',
                                'size'=>'md',
                                'options' => ['class'=>'form-control'],
                                'pluginEvents' => [
                                    "editableSuccess" => "function(event, val, form, data) { 
                                        location.reload();
                                    }",
                                ],
                            ]);
                             ?></th>
                        <th class="text-center"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->kepemimpinan : 0)?></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Jumlah</th>
                        <th class="text-center"><?=!empty($skpPerilaku) ? $skpPerilaku->total : 0;?></th>
                        <th><label id="label_jumlah_persen"></label></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Nilai rata-rata</th>
                        <th class="text-center"><?=!empty($skpPerilaku) ? round($skpPerilaku->rata_rata,2) : 0;?></th>
                        <th class="text-center">(<?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->rata_rata : 0)?>)</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>Nilai Perilaku kerja</th>
                        <th class="text-center"><?=!empty($skpPerilaku) ? round($skpPerilaku->rata_rata,2) : 0;?></th>
                        <th class="text-center">x 40 %</th>
                        <th class="text-center"><?=!empty($skpPerilaku) ? round($skpPerilaku->rata_rata * 0.4,2) : 0;?></th>
                    </tr>
                    <tr>
                        <th rowspan="2" colspan="4">Nilai Prestasi Kerja</th>
                        <th class="text-center"><?=round($bobot_capaian_skp + $bobot_avg_perilaku,2)?></th>
                        <!-- <th>b</th> -->
                    </tr>
                    <tr>
                        <th class="text-center">(<?= MyHelper::kesimpulan($total_prestasi)?>)</th>
                    </tr>
                    <tr>
                        <th colspan="2">a. Keberatan dari Pegawai yang dinilai (apabila ada)</th>
                        <th colspan="3">
                            <?php 
                            echo Editable::widget([
                                'name' => 'keberatan_pegawai_dinilai',
                                'beforeInput' => Html::hiddenInput('editableKey',$model->id),
                                'asPopover' => false,
                                'inputType' => Editable::INPUT_TEXTAREA,
                                'value' => !empty($skpPerilaku) ? $skpPerilaku->keberatan_pegawai_dinilai : null,
                                'size'=>'md',
                                'options' => ['class'=>'form-control'],
                                'pluginEvents' => [
                                    "editableSuccess" => "function(event, val, form, data) { 
                                        location.reload();
                                    }",
                                ],
                            ]);
                             ?>
                        </th>
                       
                    </tr>
                    <tr>
                        <th colspan="2">b. Keberatan dari Atasan Penilai (apabila ada)</th>
                        <th colspan="3">
                            <?php 
                            echo Editable::widget([
                                'name' => 'keberatan_atasan_penilai',
                                'beforeInput' => Html::hiddenInput('editableKey',$model->id),
                                'asPopover' => false,
                                'inputType' => Editable::INPUT_TEXTAREA,
                                'value' => !empty($skpPerilaku) ? $skpPerilaku->keberatan_atasan_penilai : null,
                                'size'=>'md',
                                'options' => ['class'=>'form-control'],
                                'pluginEvents' => [
                                    "editableSuccess" => "function(event, val, form, data) { 
                                        location.reload();
                                    }",
                                ],
                            ]);
                             ?>
                        </th>
                       
                    </tr>
                    <tr>
                        <th colspan="2">c. Keberatan dari Pejabat Penilai (apabila ada)</th>
                        <th colspan="3">
                            <?php 
                            echo Editable::widget([
                                'name' => 'keberatan_pejabat_penilai',
                                'beforeInput' => Html::hiddenInput('editableKey',$model->id),
                                'asPopover' => false,
                                'inputType' => Editable::INPUT_TEXTAREA,
                                'value' => !empty($skpPerilaku) ? $skpPerilaku->keberatan_pejabat_penilai  : null,
                                'size'=>'md',
                                'options' => ['class'=>'form-control'],
                                'pluginEvents' => [
                                    "editableSuccess" => "function(event, val, form, data) { 
                                        location.reload();
                                    }",
                                ],
                            ]);
                             ?>
                        </th>
                       
                    </tr>
                    <tr>
                        <th colspan="2">d. Keputusan Atasan Pejabat Penilai Atas Keberatan</th>
                        <th colspan="3">
                            <?php 
                            echo Editable::widget([
                                'name' => 'keputusan_atasan_pejabat_atas_keberatan',
                                'beforeInput' => Html::hiddenInput('editableKey',$model->id),
                                'asPopover' => false,
                                'inputType' => Editable::INPUT_TEXTAREA,
                                'value' => !empty($skpPerilaku) ? $skpPerilaku->keputusan_atasan_pejabat_atas_keberatan : null,
                                'size'=>'md',
                                'options' => ['class'=>'form-control'],
                                'pluginEvents' => [
                                    "editableSuccess" => "function(event, val, form, data) { 
                                        location.reload();
                                    }",
                                ],
                            ]);
                             ?>
                        </th>
                       
                    </tr>
                    <tr>
                        <th colspan="2">e. Rekomendasi</th>
                        <th colspan="3">
                            <?php 
                            echo Editable::widget([
                                'name' => 'rekomendasi',
                                'beforeInput' => Html::hiddenInput('editableKey',$model->id),
                                'asPopover' => false,
                                'inputType' => Editable::INPUT_TEXTAREA,
                                'value' => !empty($skpPerilaku) ? $skpPerilaku->rekomendasi : null,
                                'size'=>'md',
                                'options' => ['class'=>'form-control'],
                                'pluginEvents' => [
                                    "editableSuccess" => "function(event, val, form, data) { 
                                        location.reload();
                                    }",
                                ],
                            ]);
                             ?>
                        </th>
                       
                    </tr>
                </tbody>
            </table>



            </div>
        </div>

    </div>
</div>



<?php 

$this->registerJs(' 


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