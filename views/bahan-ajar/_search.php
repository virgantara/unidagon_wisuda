<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BahanAjarSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bahan-ajar-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sister_id') ?>

    <?= $form->field($model, 'id_kategori_capaian_luaran') ?>

    <?= $form->field($model, 'id_penelitian_pengabdian') ?>

    <?= $form->field($model, 'id_jenis_bahan_ajar') ?>

    <?php // echo $form->field($model, 'judul') ?>

    <?php // echo $form->field($model, 'nama_penerbit') ?>

    <?php // echo $form->field($model, 'isbn') ?>

    <?php // echo $form->field($model, 'tanggal_terbit') ?>

    <?php // echo $form->field($model, 'sk_penugasan') ?>

    <?php // echo $form->field($model, 'tanggal_sk_penugasan') ?>

    <?php // echo $form->field($model, 'nama_jenis') ?>

    <?php // echo $form->field($model, 'id_kategori_kegiatan') ?>

    <?php // echo $form->field($model, 'NIY') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
