<?php

use yii\helpers\Html;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BukuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
Yii::$app->setHomeUrl(['/site/homelog']);
$this->title = 'Buku';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3><?=$this->title;?></h3>
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
                        
                    ['class' => 'yii\grid\ActionColumn']
                ];?>    

                <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
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