<?php

use app\helpers\MyHelper;
use Google\Service\Slides\Placeholder;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Peserta */
/* @var $form yii\widgets\ActiveForm */
?>
<form id="form">
    <ul id="progressbar">
        <li id="step1">
            <strong>Biodata</strong>
        </li>
        <li id="step2"><strong>Data Orang Tua</strong></li>
        <li id="step3"><strong>Bukti Wisuda</strong></li>
        <li id="step4" class="active"><strong>Data Wisuda</strong></li>
        <li id="step5"><strong>Konfirmasi</strong></li>
    </ul>


</form>

<div class="peserta-form ">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model, ['header' => '<div class="alert alert-danger">', 'footer' => '</div>']); ?>

    <?= $form->field($model, 'ukuran_kaos', ['options' => ['tag' => false]])
        ->dropDownList(
            MyHelper::getUkuranKaos(), // Array of options
            ['class' => 'form-control', 'prompt' => 'Pilih ukuran kaos', 'required' => true] // Additional attributes
        );

    ?>

    <?= $form->field($model, 'jumlah_rombongan', ['options' => ['tag' => false]])->input('number', ['class' => 'form-control', 'placeholder' => 'Masukkan jumlah rombongan', 'required' => "required"]);   ?>


    <div class="form-group">
        <div class="pull-left">
            <?= Html::a('<i class="fa fa-arrow-left"></i> Prev ', ['peserta/create', 'step' => 3], ['class' => 'btn btn-default']) ?>
        </div>
        <div class="pull-right">

            <?= Html::submitButton('Next <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>