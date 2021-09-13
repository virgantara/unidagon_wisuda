<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BahanAjarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bahan Ajar';
$this->params['breadcrumbs'][] = $this->title;

$list_jenis_bahan_ajar = ArrayHelper::map(\app\models\JenisBahanAjar::find()->orderBy(['nama'=>SORT_ASC])->all(),'nama','nama');
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
        <?= Html::a('Create Bahan Ajar', ['create'], ['class' => 'btn btn-success']) ?>
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

            // 'id_kategori_capaian_luaran',
            // 'id_penelitian_pengabdian',
            
            'judul',
            // 
            'isbn',
            'tanggal_terbit',
            'nama_penerbit',
            [
                'attribute' => 'id_jenis_bahan_ajar',
                'filter' => $list_jenis_bahan_ajar,
                'value' => function($data){
                    return !empty($data->jenisBahanAjar) ? $data->jenisBahanAjar->nama : null;
                }
            ],
            //'sk_penugasan',
            //'tanggal_sk_penugasan',
            //'nama_jenis',
            //'id_kategori_kegiatan',
            //'NIY',
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

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3>Buku Ajar</h3>
            </div>
            <div class="panel-body">
                <p>
                    <?= Html::a('Tambah Data', ['create'], ['class' => 'btn btn-success']) ?>
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
                            'attribute' => 'judul',
                            'contentOptions' => ['style' => 'width:30%;  white-space: normal;'],
                        ],
                        'id_jenis_bahan_ajar',
                        // 'namaJenisPublikasi',
                        'ISBN',
                        'tanggal_terbit',
                        'penerbit',
                        [
                            'attribute'=>'f_karya',
                            'format'=>'raw',
                            'value' => function($data){
                                if(!empty($data->f_karya)){
                                    return Html::a('<i class="fa fa-search"></i> View', $data->f_karya,['class' => 'btn btn-warning','target'=>'_blank']);
                                }

                                else
                                {
                                    return "<p class='btn btn-danger' align='center'>No File</p>";
                                }
                            }
                        ],
                        
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'urlCreator' => function ($action, $model, $key, $index) {
                            if($action == 'delete')
                            {
                              return Url::to(['buku/delete','id'=>$model->ID]); 
                            }

                            else if($action == 'view')
                            {
                              return Url::to(['buku/view','id'=>$model->ID]); 
                            }

                            else if($action == 'update')
                            {
                              return Url::to(['buku/update','id'=>$model->ID]); 
                            }
                        }
                    ]
                ];?>    

                <?= GridView::widget([
                        'dataProvider' => $dataProviderBuku,
                        'filterModel' => $searchModelBuku,
                        'responsiveWrap' => false,
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