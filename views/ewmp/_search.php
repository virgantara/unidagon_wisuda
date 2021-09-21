<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EwmpSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ewmp-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'NIY') ?>

    <?= $form->field($model, 'is_dtps') ?>

    <?= $form->field($model, 'pendidikan_sks_ps') ?>

    <?= $form->field($model, 'pendidikan_sks_ps_lain') ?>

    <?php // echo $form->field($model, 'pendidikan_sks_pt_lain') ?>

    <?php // echo $form->field($model, 'penelitian') ?>

    <?php // echo $form->field($model, 'abdimas') ?>

    <?php // echo $form->field($model, 'penunjang') ?>

    <?php // echo $form->field($model, 'total_sks') ?>

    <?php // echo $form->field($model, 'tahun_akademik') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
