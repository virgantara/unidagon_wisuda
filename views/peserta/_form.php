<?php

use app\helpers\MyHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Peserta */
/* @var $form yii\widgets\ActiveForm */
?>

<form id="form">
    <ul id="progressbar">
        <li class="active" id="step1">
            <strong>Biodata</strong>
        </li>
        <li id="step2"><strong>Data Orang Tua</strong></li>
        <li id="step3"><strong>Bukti Wisuda</strong></li>
        <li id="step4"><strong>Data Wisuda</strong></li>
        <li id="step5"><strong>Konfirmasi</strong></li>
    </ul>


</form>

<?php $form = ActiveForm::begin(); ?>
<?= $form->errorSummary($model, ['header' => '<div class="alert alert-danger">', 'footer' => '</div>']); ?>
<div class="col-lg-6 col-md-12 col-sm-12">



    <?= $form->field($model, 'nim')->textInput(['class' => 'form-control', 'readonly' => true]) ?>

    <?= $form->field($model, 'nik')->textInput(['class' => 'form-control', 'readonly' => true]) ?>

    <?= $form->field($model, 'nama_lengkap')->textInput(['class' => 'form-control', 'readonly' => true]) ?>

    <?= $form->field($model, 'fakultas')->textInput(['class' => 'form-control', 'readonly' => true]) ?>

    <?= $form->field($model, 'prodi')->textInput(['class' => 'form-control', 'readonly' => true]) ?>

    <?= $form->field($model, 'kampus')->hiddenInput(['class' => 'form-control', 'readonly' => true])->label(false) ?>

    <label class="control-label" for="peserta-kampus">Kampus</label>

    <input type="text" class="form-control" value="<?= MyHelper::getKampus($model->kampus) ?>" readonly>

    <div class="help-block"></div>

    <?= $form->field($model, 'tempat_lahir')->textInput(['class' => 'form-control', 'readonly' => true]) ?>

    <?= $form->field($model, 'tanggal_lahir')->textInput(['class' => 'form-control', 'readonly' => true]) ?>


</div>

<div class="col-lg-6 col-md-12 col-sm-12">


    <?= $form->field($model, 'jenis_kelamin')->textInput(['class' => 'form-control', 'readonly' => true]) ?>

    <?= $form->field($model, 'status_warga')->textInput(['class' => 'form-control', 'readonly' => true]) ?>

    <?= $form->field($model, 'warga_negara')->textInput(['class' => 'form-control', 'readonly' => true]) ?>

    <?= $form->field($model, 'alamat')->textInput(['class' => 'form-control', 'readonly' => true]) ?>

    <?= $form->field($model, 'no_telp')->textInput(['class' => 'form-control', 'readonly' => true]) ?>



</div>
<div class="row">
    <div class="col-md-12">
        <div class="pull-right">
            <?= Html::submitButton('Next <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>