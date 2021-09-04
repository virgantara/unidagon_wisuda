<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SertifikasiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sertifikasi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'jenis_sertifikasi') ?>

    <?= $form->field($model, 'bidang_studi') ?>

    <?= $form->field($model, 'tahun_sertifikasi') ?>

    <?= $form->field($model, 'sk_sertifikasi') ?>

    <?php // echo $form->field($model, 'nomor_registrasi') ?>

    <?php // echo $form->field($model, 'NIY') ?>

    <?php // echo $form->field($model, 'id_jenis_sertifikasi') ?>

    <?php // echo $form->field($model, 'id_bidang_studi') ?>

    <?php // echo $form->field($model, 'sister_id') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
