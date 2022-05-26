<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PeriodeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="periode-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_periode') ?>

    <?= $form->field($model, 'nama_periode') ?>

    <?= $form->field($model, 'tahun') ?>

    <?= $form->field($model, 'tanggal_buka') ?>

    <?= $form->field($model, 'tanggal_tutup') ?>

    <?php // echo $form->field($model, 'status_aktivasi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
