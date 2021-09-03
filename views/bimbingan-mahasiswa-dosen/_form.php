<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BimbinganMahasiswaDosen */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bimbingan-mahasiswa-dosen-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'NIY',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'id_sdm',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'nama',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'kategori_kegiatan',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'urutan',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'bimbingan_mahasiswa_id',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'created_at',['options' => ['tag' => false]])->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
