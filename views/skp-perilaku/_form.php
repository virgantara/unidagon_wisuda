<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SkpPerilaku */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="skp-perilaku-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'skp_id',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'orientasi',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'integritas',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'komitmen',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'disiplin',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'kerjasama',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'kepemimpinan',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'total',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'rata_rata',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'updated_at',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'created_at',['options' => ['tag' => false]])->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
