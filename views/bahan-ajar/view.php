<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\BahanAjar */

$this->title = (!empty($model->kategoriCapaianLuaran) ? $model->kategoriCapaianLuaran->nama : null).' - '.$model->judul;
$this->params['breadcrumbs'][] = ['label' => 'Bahan Ajars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
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
        
<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            // 'sister_id',
            [
                'attribute' => 'id_kategori_capaian_luaran',
                'value' => function($data){
                    return !empty($data->kategoriCapaianLuaran) ? $data->kategoriCapaianLuaran->nama : null;
                }
            ],
            // 'id_penelitian_pengabdian',
            [
                'attribute' => 'id_jenis_bahan_ajar',
                'value' => function($data){
                    return !empty($data->jenisBahanAjar) ? $data->jenisBahanAjar->nama : null;
                }
            ],
            [
                'attribute' => 'id_kategori_kegiatan',
                'value' => function($data){
                    return !empty($data->kategoriKegiatan) ? $data->kategoriKegiatan->nama : null;
                }
            ],
            'judul',
            'nama_penerbit',
            'isbn',
            'tanggal_terbit',
            'sk_penugasan',
            'tanggal_sk_penugasan',
            'nama_jenis',
            'updated_at',
            'created_at',
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Penulis</h3>
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
            // 'id',
            // 'bahan_ajar_id',
            // 'NIY',
            'nama',
            'urutan',
            'afiliasi',
            'peran',
            'jenis',
            //'id_sdm',
            //'updated_at',
            //'created_at',
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{delete}',
        'urlCreator' => function ($action, $model, $key, $index) {
            if($action == 'delete')
            {
              return Url::to(['penulis/delete','id'=>$model->id]); 
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


    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-heading">
                
                    <h3 class="panel-title">File dari SISTER</h3>
                
            </div>
            <div class="panel-body">
                <h4>
                <ol>
                <?php 

                $sisterFiles = \app\models\SisterFiles::find()->where(['parent_id'=>$model->sister_id])->all();
                foreach($sisterFiles as $file)
                {
                    echo '<li>'.Html::a($file->nama_dokumen,$file->tautan,['target'=>'_blank']).'</li>';
                }
                ?>
                </ol>
                </h4>
            </div>
        </div>
    </div>
</div>