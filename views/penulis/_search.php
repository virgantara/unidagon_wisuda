<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PenulisSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="penulis-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'bahan_ajar_id') ?>

    <?= $form->field($model, 'NIY') ?>

    <?= $form->field($model, 'nama') ?>

    <?= $form->field($model, 'urutan') ?>

    <?php // echo $form->field($model, 'afiliasi') ?>

    <?php // echo $form->field($model, 'peran') ?>

    <?php // echo $form->field($model, 'jenis') ?>

    <?php // echo $form->field($model, 'id_sdm') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
