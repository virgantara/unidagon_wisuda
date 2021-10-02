<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
$list_jenis_tes = ArrayHelper::map(\app\models\JenisTes::find()->all(),'id', 'nama');

/* @var $this yii\web\View */
/* @var $model app\models\Tes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tes-form">

    <?php $form = ActiveForm::begin(); ?>


     <?= $form->field($model, 'id_jenis_tes',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $list_jenis_tes,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Jenis Tes -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>

    <?= $form->field($model, 'nama',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'penyelenggara',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal',['options' => ['tag' => false]])->widget(
            DatePicker::className(),[
                'name' => 'tanggal', 
                'value' => date('Y-m-d', strtotime('0 days')),
                'options' => ['placeholder' => 'Pilih tanggal ...'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]
        ) ?>


    <?= $form->field($model, 'tahun',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'skor',['options' => ['tag' => false]])->textInput() ?>

   

    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
