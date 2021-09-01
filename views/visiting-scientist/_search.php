<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\VisitingScientistSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visiting-scientist-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'perguruan_tinggi_pengundang') ?>

    <?= $form->field($model, 'durasi_kegiatan') ?>

    <?= $form->field($model, 'tanggal_pelaksanaan') ?>

    <?= $form->field($model, 'kategori_kegiatan_id') ?>

    <?php // echo $form->field($model, 'nama_penelitian_pengabdian') ?>

    <?php // echo $form->field($model, 'id_penelitian_pengabdian') ?>

    <?php // echo $form->field($model, 'nama_kategori_pencapaian') ?>

    <?php // echo $form->field($model, 'id_kategori_capaian_luaran') ?>

    <?php // echo $form->field($model, 'id_universitas') ?>

    <?php // echo $form->field($model, 'kegiatan_penting_yang_dilakukan') ?>

    <?php // echo $form->field($model, 'no_sk_tugas') ?>

    <?php // echo $form->field($model, 'tanggal_sk_penugasan') ?>

    <?php // echo $form->field($model, 'durasi') ?>

    <?php // echo $form->field($model, 'NIY') ?>

    <?php // echo $form->field($model, 'sister_id') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
