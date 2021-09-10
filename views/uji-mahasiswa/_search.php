<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UjiMahasiswaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="uji-mahasiswa-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_aktivitas') ?>

    <?= $form->field($model, 'judul') ?>

    <?= $form->field($model, 'id_uji') ?>

    <?= $form->field($model, 'id_kategori_kegiatan') ?>

    <?php // echo $form->field($model, 'nama_kategori_kegiatan') ?>

    <?php // echo $form->field($model, 'id_dosen') ?>

    <?php // echo $form->field($model, 'NIY') ?>

    <?php // echo $form->field($model, 'penguji_ke') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
