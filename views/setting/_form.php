<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
/* @var $this yii\web\View */
/* @var $model app\models\Setting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setting-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kode_setting')->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'konten')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'advance',  

    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
