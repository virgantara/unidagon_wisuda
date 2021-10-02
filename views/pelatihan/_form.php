<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\models\Pelatihan */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading"></div>
            <div class="panel-body">

    <?php $form = ActiveForm::begin(); ?>
      
    
    <?= $form->field($model, 'nama_pelatihan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'penyelenggara')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'tanggal_awal',['options' => ['tag' => false]])->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Input tanggal awal ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,
                    ]
                ]);
                 ?>

    
         <?= $form->field($model, 'tanggal_akhir',['options' => ['tag' => false]])->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Input tanggal akhir ...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,
                    ]
                ]);
                 ?>
    <?= $form->field($model, 'f_sertifikat')->fileInput().'NB: File format is pdf, png, jpeg, jpg and maximal sized 1 MB<br><br>' ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
