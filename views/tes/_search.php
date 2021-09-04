<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'jenis_tes') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'penyelenggara') ?>

    <?= $form->field($model, 'tahun') ?>

    <?php // echo $form->field($model, 'skor') ?>

    <?php // echo $form->field($model, 'NIY') ?>

    <?php // echo $form->field($model, 'id_jenis_tes') ?>

    <?php // echo $form->field($model, 'tanggal') ?>

    <?php // echo $form->field($model, 'sister_id') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
