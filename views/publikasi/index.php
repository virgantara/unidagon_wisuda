<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PublikasiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Publikasi';
$this->params['breadcrumbs'][] = $this->title;

$listKegiatan = \app\helpers\MyHelper::convertKategoriKegiatan('120');
$listJenisPublikasi = ArrayHelper::map(\app\models\JenisPublikasi::find()->all(),'kode',function($data){
    return $data->kode.' - '.$data->nama;
});

$query = \app\models\KomponenKegiatan::find();
$query->alias('p');
$query->select(['p.nama']);
$query->joinWith(['unsur as u']);
$query->where([
  'u.kode' => 'RISET'
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

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
<div class="panel-body ">
    <p>
    <?php

    $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
    

    if(empty($user->sister_id)){

        echo Html::a('<i class="fa fa-plus"></i> Publikasi', ['create'], ['class' => 'btn btn-success']);
    } 

    else{
        echo Html::a('<i class="fa fa-download"></i> Import Publikasi dari SISTER', 'javascript:void(0)', ['class' => 'btn btn-success','id'=>'btn-import']);  
    }

    ?>
    </p>
    <?php 
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
      echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
    }
    ?>
    <?php 
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
      echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
    }
    ?>

    
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
            // 'id',

            'judul_publikasi_paten',
            // 'nama_jenis_publikasi',
            [
                'attribute' => 'kategori_kegiatan_id',
                'class' => 'kartik\grid\EditableColumn',
                'refreshGrid' => true,
                'filter' => $listKegiatan,
                'editableOptions' => [
                    'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                    'asPopover' => false,
                    'data' => $listKegiatan
                ],
                'value' => function($data){
                    return !empty($data->kategoriKegiatan) ? $data->kategoriKegiatan->nama : '';
                }
            ],
            [
                'attribute' => 'jenis_publikasi_id',
                'class' => 'kartik\grid\EditableColumn',
                'refreshGrid' => true,
                'filter' => $listJenisPublikasi,
                'editableOptions' => [
                    'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                    'asPopover' => false,
                    'data' => $listJenisPublikasi
                ],
                'value' => function($data){
                    return !empty($data->jenisPublikasi) ? $data->jenisPublikasi->nama : '';
                }
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'kegiatan_id',
                'filter' => $listKomponenKegiatan,
                'refreshGrid' => true,
                'editableOptions' => [
                    'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                    'asPopover' => false,
                    'data' => $listKomponenKegiatan
                ],
                'value' => function($data){
                    return !empty($data->kegiatan) ? $data->kegiatan->subunsur : '';
                }
            ],
            [
                'attribute' => 'tanggal_terbit',
                'filterType' => GridView::FILTER_DATE_RANGE,
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'jumlah_sitasi',
                'refreshGrid' => true,
                'editableOptions' => [
                    'inputType' => \kartik\editable\Editable::INPUT_TEXT,
                    'asPopover' => false
                ],
            ],
            'sister_id',
            //'updated_at',
            //'created_at',
    ['class' => 'yii\grid\ActionColumn']
];?>    
<p>
<?php 
// Renders a export dropdown menu
echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
    'clearBuffers' => true, //optional
]);
?>
</p>
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
                    ['content'=> $this->title, 'options'=>['colspan'=>9, 'class'=>'text-center warning']], //cuma satu 
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
            // '{export}', 

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

$this->registerJs(' 
   
$(document).on("click","#btn-import",function(e){
    e.preventDefault()
    $.ajax({
        url: "'.\yii\helpers\Url::to(["publikasi/ajax-import"]).'",
        type: "POST",
        beforeSend : function(){
            Swal.fire({
                title: \'Please Wait !\',
                html: \'Importing data\',
                allowOutsideClick: false,
                showCancelButton: false, 
                showConfirmButton: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
            });
        },
        error : function(e){

            Swal.fire(\'Oops...\', e.responseText, \'error\')
        },
        success: function (data) {
            Swal.close();
            var res = $.parseJSON(data)
            var elapsedTime = Math.round(res.elapsed_time * 1000) / 1000
            if(res.code == 200){
                $("#tabel-sync > tbody").empty();
                var row = "";
                $(res.items).each(function(i,obj){
                    row += "<tr>";
                    row += "<td>"+eval(i+1)+"</td>";
                    row += "<td>"+obj.modul+"</td>";
                    row += "<td>"+obj.data+"</td>";
                    row += "<td>"+obj.source+"</td>";
                    row += "</tr>";
                })

                $("#tabel-sync > tbody").append(row);
                Swal.fire({
                    title: \'Yeay!\',
                    icon: \'success\',
                    html: "Import succeeded. <br>Elapsed time: "+elapsedTime+" secs. <br>"+res.message,
                })
                $.pjax.reload({container: "#pjax-container"})
            }

            else{
                Swal.fire(\'Oops...\', res.message, \'error\')
            }
        }
    })
}); 
', \yii\web\View::POS_READY);

?>
