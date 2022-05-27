<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Periode */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="periode-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama_periode',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'tahun',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_buka',['options' => ['tag' => false]])->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => 'Pilih Tanggal Buka ...'],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd hh:ii:ss',
            'todayHighlight' => true,
            'autoclose' => true
        ]
    ]) ?>
    <?= $form->field($model, 'tanggal_tutup',['options' => ['tag' => false]])->widget(DateTimePicker::className(),[
        'options' => ['placeholder' => 'Pilih Tanggal Tutup ...'],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd hh:ii:ss',
            'todayHighlight' => true,
            'autoclose' => true
        ]
    ]) ?>

    <?= $form->field($model, 'status_aktivasi',['options' => ['tag' => false]])->radioList(['Y'=>'Buka','N'=>'Tutup']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
