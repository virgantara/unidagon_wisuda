<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Ewmp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ewmp-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'NIY',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'is_dtps',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'pendidikan_sks_ps',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'pendidikan_sks_ps_lain',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'pendidikan_sks_pt_lain',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'penelitian',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'abdimas',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'penunjang',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'total_sks',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'tahun_akademik',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'updated_at',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'created_at',['options' => ['tag' => false]])->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
