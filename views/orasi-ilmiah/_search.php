<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrasiIlmiahSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orasi-ilmiah-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'NIY') ?>

    <?= $form->field($model, 'sister_id') ?>

    <?= $form->field($model, 'nama_kategori_kegiatan') ?>

    <?= $form->field($model, 'nama_kategori_pencapaian') ?>

    <?php // echo $form->field($model, 'id_kategori_capaian_luaran') ?>

    <?php // echo $form->field($model, 'kategori_kegiatan_id') ?>

    <?php // echo $form->field($model, 'judul_buku_makalah') ?>

    <?php // echo $form->field($model, 'nama_pertemuan_ilmiah') ?>

    <?php // echo $form->field($model, 'penyelenggara_kegiatan') ?>

    <?php // echo $form->field($model, 'tanggal_pelaksanaan') ?>

    <?php // echo $form->field($model, 'id_kategori_pembicara') ?>

    <?php // echo $form->field($model, 'no_sk_tugas') ?>

    <?php // echo $form->field($model, 'tanggal_sk_penugasan') ?>

    <?php // echo $form->field($model, 'bahasa') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
