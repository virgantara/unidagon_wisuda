<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Tendik */

$this->title = 'Update Tenaga Kependidikan: ' . $model->NIY;
$this->params['breadcrumbs'][] = ['label' => 'Tenaga Kependidikan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->NIY, 'url' => ['view', 'id' => $model->NIY]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?= $this->render('_form', [
                    'model' => $model,
                    'user' => $user
                ]) ?>        
            </div>
        </div>
    </div>
</div>
