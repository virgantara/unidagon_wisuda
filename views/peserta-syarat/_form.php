<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PesertaSyarat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="peserta-syarat-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'peserta_id',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'syarat_id',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'file_path',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
