<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PenghargaanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="penghargaan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'NIY') ?>

    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'bentuk') ?>

    <?= $form->field($model, 'pemberi') ?>

    <?php // echo $form->field($model, 'f_penghargaan') ?>

    <?php // echo $form->field($model, 'ver') ?>

    <?php // echo $form->field($model, 'sister_id') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
