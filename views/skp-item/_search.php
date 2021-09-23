<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SkpItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="skp-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'skp_id') ?>

    <?= $form->field($model, 'komponen_kegiatan_id') ?>

    <?= $form->field($model, 'target_ak') ?>

    <?= $form->field($model, 'target_qty') ?>

    <?php // echo $form->field($model, 'target_satuan') ?>

    <?php // echo $form->field($model, 'target_mutu') ?>

    <?php // echo $form->field($model, 'target_waktu') ?>

    <?php // echo $form->field($model, 'target_waktu_satuan') ?>

    <?php // echo $form->field($model, 'target_biaya') ?>

    <?php // echo $form->field($model, 'realisasi_ak') ?>

    <?php // echo $form->field($model, 'realisasi_qty') ?>

    <?php // echo $form->field($model, 'realisasi_satuan') ?>

    <?php // echo $form->field($model, 'realisasi_mutu') ?>

    <?php // echo $form->field($model, 'realisasi_waktu') ?>

    <?php // echo $form->field($model, 'realisasi_waktu_satuan') ?>

    <?php // echo $form->field($model, 'realisasi_biaya') ?>

    <?php // echo $form->field($model, 'capaian') ?>

    <?php // echo $form->field($model, 'capaian_skp') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
