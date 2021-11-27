<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PengabdianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pengabdian';
$this->params['breadcrumbs'][] = $this->title;

$list_jenis_sumber_dana = \app\helpers\MyHelper::listJenisSumberDana();

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
    <?php

    $user = \app\models\User::findOne(Yii::$app->user->identity->ID);
    

    if(!empty($dataDiri) && empty($user->sister_id)){

        echo Html::a('<i class="fa fa-plus"></i> Pengabdian', ['create'], ['class' => 'btn btn-success']);
    } 

    else{
        echo Html::a('<i class="fa fa-download"></i> Import Pengabdian dari SISTER', 'javascript:void(0)', ['class' => 'btn btn-success','id'=>'btn-import']);  
    }

    ?>
    </p>
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
            // 'ID',
            // 'NIY',
            'judul_penelitian_pengabdian',
            'nama_tahun_ajaran',
            'nama_skim',
            'durasi_kegiatan',
            'no_sk_tugas',
            'tgl_sk_tugas',
            [
                'attribute' => 'jenis_sumber_dana',
                'filter' => $list_jenis_sumber_dana,
                'value' => function($data) use ($list_jenis_sumber_dana){
                    return $list_jenis_sumber_dana[$data->jenis_sumber_dana];
                }
            ],
            // [
            //     'attribute' => 'jenis_penelitian_pengabdian',
            //     'value' => function($data){
            //         return $data->jenis_penelitian_pengabdian == 'M' ? 'Pengabdian' : 'Penelitian';
            //     }
            // ],
            //'nilai',
            //'sister_id',
            //'updated_at',
            //'created_at',
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
        url: "'.\yii\helpers\Url::to(["pengabdian/ajax-import"]).'",
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
