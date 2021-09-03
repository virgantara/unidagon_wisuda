<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Penghargaan */

$this->title = $model->bentuk;
$this->params['breadcrumbs'][] = ['label' => 'Penghargaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <?= Html::a('Update', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->ID], [
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
            'ID',
            'NIY',
            'tahun',
            'bentuk',
            'pemberi',
            'f_penghargaan',
            'ver',
            'sister_id',
            'updated_at',
            'created_at',
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