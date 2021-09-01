<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrasiIlmiah */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orasi-ilmiah-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'NIY',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'sister_id',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'nama_kategori_kegiatan',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'nama_kategori_pencapaian',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'id_kategori_capaian_luaran',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'kategori_kegiatan_id',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'judul_buku_makalah',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'nama_pertemuan_ilmiah',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'penyelenggara_kegiatan',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_pelaksanaan',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'id_kategori_pembicara',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'no_sk_tugas',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_sk_penugasan',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'bahasa',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'created_at',['options' => ['tag' => false]])->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
