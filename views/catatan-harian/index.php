<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CatatanHarianSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Catatan Harian';
$this->params['breadcrumbs'][] = $this->title;

$list_skp = ArrayHelper::map(\app\models\Skp::find()->where([
    'pegawai_dinilai' => Yii::$app->user->identity->NIY,
    'status_skp' => 2
])->orderBy(['periode_id' => SORT_DESC])->all(),'id','periode_id');
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
        <?= Html::a('Create Catatan Harian', ['create'], ['class' => 'btn btn-success','id' => 'btn-add']) ?>
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
                'attribute' => 'skp_item_id',
                'value' => function($data){
                    return !empty($data->skpItem) ? $data->skpItem->nama : '-';
                }
            ],
            
            // [
            //     'attribute' => 'user_id',
            //     'value' => function($data){
            //         return !empty($data->user->dataDiri) ? $data->user->dataDiri->nama : '-';
            //     }
            // ],
            'deskripsi:html',
            
            'tanggal',
            'poin',
            //'updated_at',
            //'created_at',
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view} {update} {delete}',
        'buttons' => [
            'update' => function ($url, $model){
              return Html::a('<span class="glyphicon glyphicon-edit"></span>', '#', [
                        'title' => Yii::t('app', 'Update Catatan'),
                        'class' => 'btn-update',
                        'data-item' => $model->id
              ]);
            }
          ],
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
                    <td width="30%">Periode SKP <span style="color:red">*</span></td>
                    <td width="70%">
                        <?= Html::hiddenInput('user_id',Yii::$app->user->identity->ID) ?>
                        <?= Select2::widget([
                            'name' => 'skp_id',
                            'data' => $list_skp,
                            'options'=>['tabindex'=>'0','id'=>'skp_id','placeholder'=>Yii::t('app','- Pilih -')],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'dropdownParent' => '#modal'
                            ],
                        ])?>
                        
                    </td>
                </tr>
                <tr>
                    <td width="30%">Item Kegiatan <span style="color:red">*</span></td>
                    <td width="70%">
                         <?php 
                        echo DepDrop::widget([
                            'name' => 'skp_item_id',
                            'type'=>DepDrop::TYPE_SELECT2,
                            'options'=>['id'=>'skp_item_id'],
                            'select2Options'=>[
                                'pluginOptions'=>[
                                    'allowClear'=>true,
                                    'dropdownParent' => '#modal'
                                ]
                            ],
                            'pluginOptions'=>[
                                'depends'=>['skp_id'],
                                'initialize' => true,
                                'placeholder'=>'- Pilih -',
                                'url'=>\yii\helpers\Url::to(['/skp-item/subitem'])
                            ]
                            
                        ]);
                        ?>
                        
                    </td>
                </tr>
                <tr>
                    <td>Deskripsi <span style="color:red">*</span></td>
                    <td><?= Html::textInput('deskripsi','',['class'=>'form-control','id'=>'deskripsi']) ?></td>
                    
                </tr>
                <tr>
                    <td>Tanggal Kegiatan <span style="color:red">*</span></td>
                    <td>
                        
                        <?= DatePicker::widget([
                            'name' => 'tanggal',
                            'options' => ['id' => 'tanggal'],
                            'value' => date('Y-m-d'),
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true,
                            ]
                         ]) ?>        
                    </td>
                    
                </tr>
                
                
            </table>
            
           
          
            
            <div class="form-group">
                <button id="btn-simpan-catatan" class="btn btn-primary btn-block">
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


yii\bootstrap\Modal::begin([
'headerOptions' => ['id' => 'modalHeader_update'],
'id' => 'modal_update',
'size' => 'modal-lg',
'clientOptions' => ['backdrop' => 'static', 'keyboard' => true]
]);
?>

<form action="" id="form-skp-temp_update">
    <div class="row">
        <div class="col-md-12">
            <table class="table">

                <tr>
                    <td width="30%">Periode SKP <span style="color:red">*</span></td>
                    <td width="70%">
                        <?= Html::hiddenInput('id','',['id'=>'id_update']) ?>
                        <?= Html::hiddenInput('user_id',Yii::$app->user->identity->ID,['id'=>'user_id_update']) ?>
                        <?= Html::dropDownList('skp_id','',$list_skp,['id'=>'skp_id_update','placeholder'=>Yii::t('app','- Pilih -'),'class'=>'form-control'])?>
                        
                    </td>
                </tr>
                <tr>
                    <td width="30%">Item Kegiatan <span style="color:red">*</span></td>
                    <td width="70%">
                        <?= Html::dropDownList('skp_item_id','',[],['id'=>'skp_item_id_update','placeholder'=>Yii::t('app','- Pilih -'),'class'=>'form-control'])?>
                      
                    </td>
                </tr>
                <tr>
                    <td>Deskripsi <span style="color:red">*</span></td>
                    <td><?= Html::textInput('deskripsi','',['class'=>'form-control','id'=>'deskripsi_update']) ?></td>
                    
                </tr>
                <tr>
                    <td>Tanggal Kegiatan <span style="color:red">*</span></td>
                    <td>
                        
                        <?= DatePicker::widget([
                            'name' => 'tanggal',
                            'options' => ['id' => 'tanggal_update'],
                            'value' => date('Y-m-d'),
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true,
                            ]
                         ]) ?>        
                    </td>
                    
                </tr>
                
                
            </table>
            
           
          
            
            <div class="form-group">
                <button id="btn-simpan-catatan_update" class="btn btn-primary btn-block">
                    <i class="fa fa-save"></i> Update
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
   $("#skp_item_id").val("")
   $("#deskripsi").val("")
   
})


$(document).on("click", "#btn-add", function(e){
    e.preventDefault();
    $("#modal").modal("show")
    
});

$(document).on("change", "#skp_id_update", function(e){
    $.ajax({
        url: "'.Url::to(["skp-item/ajax-list"]).'",
        type : "POST",
        async : true,
        data: "id="+$(this).val(),
        error : function(e){
            console.log(e.responseText)
        },
        beforeSend: function(){
            Swal.showLoading()
        },
        success: function (data) {
            Swal.close()
            var hasil = $.parseJSON(data)
            $("#skp_item_id_update").empty()
            var row = ""
            $.each(hasil, function(i,obj){
                row += "<option value=\'"+obj.id+"\'>"+obj.name+"</option>"
            })
            
            $("#skp_item_id_update").append(row)
        }
    })
});

$(document).on("click",".btn-update", function(e){
    e.preventDefault();

    $.ajax({
        url: "'.Url::to(["catatan-harian/ajax-get"]).'",
        type : "POST",
        async : true,
        data: "id="+$(this).data("item"),
        error : function(e){
            console.log(e.responseText)
        },
        beforeSend: function(){
            Swal.showLoading()
        },
        success: function (data) {
            Swal.close()
            var hasil = $.parseJSON(data)
            $("#deskripsi_update").val(hasil.deskripsi)
            $("#tanggal_update").val(hasil.tanggal)
            $("#id_update").val(hasil.id)
            
            $("#skp_id_update").val(hasil.skp_id).trigger("change")
            //     // $("#skp_id_update").
            
            
            setTimeout(function () {
                $("#skp_item_id_update").val(hasil.skp_item_id)
            },500)
            
            $("#modal_update").modal("show")
        }
    })
})

$(document).on("click", "#btn-simpan-catatan", function(e){
    e.preventDefault();
    
    var obj = $("#form-skp-temp").serialize()
    
    $.ajax({
        url: "'.Url::to(["catatan-harian/ajax-add"]).'",
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


$(document).on("click", "#btn-simpan-catatan_update", function(e){
    e.preventDefault();
    
    var obj = $("#form-skp-temp_update").serialize()
    
    $.ajax({
        url: "'.Url::to(["catatan-harian/ajax-update"]).'",
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