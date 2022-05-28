<?php
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
        <li id="step2" class="active" ><strong>Data Orang Tua</strong></li>
        <li id="step3"><strong>Bukti Wisuda</strong></li>
        <li id="step4"><strong>Konfirmasi</strong></li>
    </ul>
    
    
</form>

<div class="peserta-form ">

    <?php $form = ActiveForm::begin(); ?>
    
<?= $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?> 

    <?= $form->field($model, 'nama_ayah',['options' => ['tag' => false]])->textInput(['class'=>'form-control','readonly' => true]) ?>

    <?= $form->field($model, 'pekerjaan_ayah',['options' => ['tag' => false]])->textInput(['class'=>'form-control','readonly' => true]) ?>

    <?= $form->field($model, 'nama_ibu',['options' => ['tag' => false]])->textInput(['class'=>'form-control','readonly' => true]) ?>

    <?= $form->field($model, 'pekerjaan_ibu',['options' => ['tag' => false]])->textInput(['class'=>'form-control','readonly' => true]) ?>

   

    <div class="form-group">
        <div class="pull-left">
            <?= Html::a('<i class="fa fa-arrow-left"></i> Prev ',['peserta/create','step'=>1], ['class' => 'btn btn-default']) ?>
        </div>
        <div class="pull-right">
            
            <?= Html::submitButton('Next <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-success']) ?>    
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
