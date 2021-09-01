<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Pt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pt-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'nama',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
