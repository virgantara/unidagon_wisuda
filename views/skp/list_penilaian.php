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

$list_status_skp = MyHelper::statusSkp();
/* @var $this yii\web\View */
/* @var $model app\models\Skp */


$nama_pegawai = '';
// $nama_pejabat_penilai = 

$list_staf = MyHelper::listRoleStaf();

if(in_array($pegawaiDinilai->access_role, $list_staf))
{
    $nama_pegawai = $pegawaiDinilai->tendik->nama;
}

else
{
    $nama_pegawai = !empty($pegawaiDinilai) ? $pegawaiDinilai->nama : '-';
}

$this->title = 'Form SKP Periode ';
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
              
            </div>

            <div class="panel-body ">
            <table class="table table-hover">
                <tbody>
                    <tr>
                        <th colspan="2"><h3>I. Pegawai Dinilai</h3></th>
                    </tr>
                    <tr>
                       
                        <th width="20%">Nama</th>
                        <th width="80%">: <?=$nama_pegawai;?></th>
                    </tr>
                    <tr>
                        <th>NIY</th>
                        <th>: <?=$pegawaiDinilai->NIY;?></th>
                    </tr>
                    <tr>
                      
                        <th>Pangkat</th>
                        <th>: 
                             <?php 
                            if(!(in_array($pegawaiDinilai->access_role, $list_staf)))
                            {
                            ?>
                            <?=$pegawaiDinilai->dataDiri->namaPangkat;?>
                                <?php } ?>
                            </th>
                    </tr>
                    <tr>
                       
                        <th>Jabatan</th>
                        <th>: 
                             <?php 
                            if(!(in_array($pegawaiDinilai->access_role, $list_staf)))
                            {
                            ?>
                            <?=$pegawaiDinilai->dataDiri->namaJabfung;?>
                                <?php } ?>
                            </th>
                    </tr>
                    <tr>
               
                        <th>Unit Kerja</th>
                        <th>: <?=!empty($jabatanPegawai) && !empty($jabatanPegawai->unker) ? $jabatanPegawai->unker->nama : '-'?></th>
                    </tr>
                    <tr>
                        <th colspan="3"><h3>II. Pejabat Penilai</h3></th>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <th>: <?=!empty($pejabatPenilai->dataDiri) ? $pejabatPenilai->dataDiri->gelar_depan.' '.$pejabatPenilai->dataDiri->nama.' '.$pejabatPenilai->dataDiri->gelar_belakang : '-'?></th>
                       
                    </tr>
                    <tr>
                        <th>NIY</th>
                        <th>: <?=!empty($pejabatPenilai) ? $pejabatPenilai->NIY : '-'?></th>
                   
                    </tr>
                    <tr>
                        <th>Pangkat</th>
                        <th>: <?=!empty($pejabatPenilai->dataDiri) ? $pejabatPenilai->dataDiri->namaPangkat : '-'?></th>
                   
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <th>: <?=!empty($pejabatPenilai->dataDiri) ? $pejabatPenilai->dataDiri->namaJabfung : '-'?></th>
                   
                    </tr>
                    <tr>
                        <th>Unit Kerja</th>
                        <th>: <?=!empty($jabatanPenilai) && !empty($jabatanPenilai->unker) ? $jabatanPenilai->unker->nama : '-'?></th>
                    </tr>
                    <tr>
                        <th colspan="3"><h3>III. Atasan Pejabat Penilai</h3></th>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <th>: <?=!empty($atasanPejabatPenilai->dataDiri) ? $atasanPejabatPenilai->dataDiri->gelar_depan.' '.$atasanPejabatPenilai->dataDiri->nama.' '.$atasanPejabatPenilai->dataDiri->gelar_belakang : '-'?></th>
                       
                    </tr>
                    <tr>
                        <th>NIY</th>
                        <th>: <?=!empty($atasanPejabatPenilai) ? $atasanPejabatPenilai->NIY : '-'?></th>
                   
                    </tr>
                    <tr>
                        <th>Pangkat</th>
                        <th>: <?=!empty($atasanPejabatPenilai->dataDiri) ? $atasanPejabatPenilai->dataDiri->namaPangkat : '-'?></th>
                   
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <th>: <?=!empty($atasanPejabatPenilai->dataDiri) ? $atasanPejabatPenilai->dataDiri->namaJabfung : '-'?></th>
                   
                    </tr>
                    <tr>
                        <th>Unit Kerja</th>
                        <th>: <?=!empty($jabatanAtasanPenilai) && !empty($jabatanAtasanPenilai->unker) ? $jabatanAtasanPenilai->unker->nama : '-'?></th>
                    </tr>
                </tbody>
            </table>

            </div>
        </div>

    </div>
</div>
<?php 
if(Yii::$app->user->identity->NIY == $pegawaiDinilai->NIY)
{
 ?>

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
                             <?=!empty($skpPerilaku) ? $skpPerilaku->orientasi : null ?>
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
                          
                            <?=!empty($skpPerilaku) ? $skpPerilaku->integritas : null?>
                        </th>
                        <th class="text-center"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->integritas : 0)?></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>3. Komitmen</th>
                        <th class="text-center">
                             <?=!empty($skpPerilaku) ? $skpPerilaku->komitmen : null?>
                        </th>
                        <th class="text-center"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->komitmen : 0)?></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>4. Disiplin</th>
                        <th class="text-center"><?=!empty($skpPerilaku) ? $skpPerilaku->disiplin : null?></th>
                        <th class="text-center"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->disiplin : 0)?></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>5. Kerjasama</th>
                        <th class="text-center"><?=!empty($skpPerilaku) ? $skpPerilaku->kerjasama : null?></th>
                        <th class="text-center"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->kerjasama : 0)?></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th>6. Kepemimpinan</th>
                        <th class="text-center"><?=!empty($skpPerilaku) ? $skpPerilaku->kepemimpinan : null?></th>
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
                            <?=!empty($skpPerilaku) ? $skpPerilaku->keberatan_pegawai_dinilai : null?>
                        </th>
                       
                    </tr>
                    <tr>
                        <th colspan="2">b. Keberatan dari Atasan Penilai (apabila ada)</th>
                        <th colspan="3">
                            <?=!empty($skpPerilaku) ? $skpPerilaku->keberatan_atasan_penilai : null?>
                        </th>
                       
                    </tr>
                    <tr>
                        <th colspan="2">c. Keberatan dari Pejabat Penilai (apabila ada)</th>
                        <th colspan="3">
                            <?=!empty($skpPerilaku) ? $skpPerilaku->keberatan_pejabat_penilai  : null?>
                        </th>
                       
                    </tr>
                    <tr>
                        <th colspan="2">d. Keputusan Atasan Pejabat Penilai Atas Keberatan</th>
                        <th colspan="3">
                            <?=!empty($skpPerilaku) ? $skpPerilaku->keputusan_atasan_pejabat_atas_keberatan : null?>
                        </th>
                       
                    </tr>
                    <tr>
                        <th colspan="2">e. Rekomendasi</th>
                        <th colspan="3">
                            <?=!empty($skpPerilaku) ? $skpPerilaku->rekomendasi : null;?>
                        </th>
                       
                    </tr>
                </tbody>
            </table>



            </div>
        </div>

    </div>
</div>

<?php } ?>

<?php 
if(!empty($dataProvider->getModels()))
{
 ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">List SKP Penilaian</h3>
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
                'attribute' => 'pegawai_dinilai',
                'value' => function($data) use ($list_staf){
                    $nama_pegawai = '';
                    // $nama_pejabat_penilai = 

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
                'template' => '{update}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action == 'update')
                    {
                        return Url::to(['skp/penilaian','id'=>$model->id]); 
                    }
                }
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


<?php } ?>
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