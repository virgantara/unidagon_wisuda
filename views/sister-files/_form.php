<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model app\models\PembicaraFiles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pembicara-files-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?>   
     <?php 
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
      echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
    }
    ?>
    <?= $form->field($model, 'nama_dokumen',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'nama_file',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'tautan',['options' => ['tag' => false]])->fileInput().'NB: File format is pdf and max size is 1 MB<br><br>' ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
