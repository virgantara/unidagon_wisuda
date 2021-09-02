<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Penelitian */
Yii::$app->setHomeUrl(['/site/homelog']);
$this->title = 'Update Penelitian: ' . $model->judul_penelitian_pengabdian;
$this->params['breadcrumbs'][] = ['label' => 'Penelitian', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->judul_penelitian_pengabdian, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body ">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
           </div>
        </div>
    </div>
</div>
