<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SkpItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="skp-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'skp_id',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'komponen_kegiatan_id',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'target_ak',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'target_qty',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'target_satuan',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'target_mutu',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'target_waktu',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'target_waktu_satuan',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'target_biaya',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'realisasi_ak',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'realisasi_qty',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'realisasi_satuan',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'realisasi_mutu',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'realisasi_waktu',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'realisasi_waktu_satuan',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'realisasi_biaya',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'capaian',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'capaian_skp',['options' => ['tag' => false]])->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
