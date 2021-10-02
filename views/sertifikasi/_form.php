<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
$list_bidang_ilmu = ArrayHelper::map(\app\models\BidangIlmu::find()->all(),'kode', 'nama');

/* @var $this yii\web\View */
/* @var $model app\models\Sertifikasi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sertifikasi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'jenis_sertifikasi',['options' => ['tag' => false]])->dropDownList(['Sertifikasi Profesi'=>'Sertifikasi Profesi','Sertifikasi Dosen' => 'Sertifikasi Dosen']) ?>
    
    <?= $form->field($model, 'id_bidang_studi',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $list_bidang_ilmu,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Bidang Studi -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>
    

   
    <?= $form->field($model, 'tahun_sertifikasi',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'sk_sertifikasi',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'nomor_registrasi',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>


   

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
