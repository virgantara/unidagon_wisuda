<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Hki */

$this->title = $model->judul;
$this->params['breadcrumbs'][] = ['label' => 'HKI', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">

    <h1><?= Html::encode($this->title) ?></h1>
     <div class="panel-body">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'jenisHki.nama',
            'no_pendaftaran',
            'judul',
            'status_hki',
            'tahun_pelaksanaan',
            'sumber_dana',
            [
                'attribute'=>'berkas',
                'format'=>'raw',
                'value' => function($data){
            if(!empty($data->berkas)){
            return
            Html::a('<i class="fa fa-search"></i> View', $data->berkas,['class' => 'btn btn-warning','target'=>'_blank']).'&nbsp;&nbsp;';
            }
            else
            {
            return
            "<p class='btn btn-danger' align='center'>No File</p>";
            }
            }
            ],
            'ver'
        ],
    ]) ?>
</div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading">
                
                    <h2>Author(s)</h2>
                
            </div>
            <div class="panel-body">
                <h4>
                <ol>
                <?php 
                foreach($model->hkiAuthors as $dosen)
                {
                    echo '<li>'.$dosen->author->dataDiri->nama.'</li>';
                }
                ?>
                </ol>
                </h4>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading">
                
                    <h2>File dari SISTER</h2>
                
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