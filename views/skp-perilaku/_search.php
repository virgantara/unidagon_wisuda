<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SkpPerilakuSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="skp-perilaku-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'skp_id') ?>

    <?= $form->field($model, 'orientasi') ?>

    <?= $form->field($model, 'integritas') ?>

    <?= $form->field($model, 'komitmen') ?>

    <?php // echo $form->field($model, 'disiplin') ?>

    <?php // echo $form->field($model, 'kerjasama') ?>

    <?php // echo $form->field($model, 'kepemimpinan') ?>

    <?php // echo $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'rata_rata') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
