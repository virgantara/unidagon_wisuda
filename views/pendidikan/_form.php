<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Pendidikan */
/* @var $form yii\widgets\ActiveForm */

$listDataJenjang = \app\models\MJenjangPendidikan::getList();
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading"></div>
            <div class="panel-body">
                
            
                <?php $form = ActiveForm::begin(); ?>

                <?=$form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?>

                <?= $form->field($model, 'tahun_lulus')->textInput() ?>

                <?= $form->field($model, 'jenjang')->dropDownList($listDataJenjang, ['prompt' => '-Pilih Jenjang-']) ?>

                <?= $form->field($model, 'perguruan_tinggi')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'jurusan')->textInput(['maxlength' => true]) ?>

                <?= $form->field($model, 'f_ijazah')->fileInput().'NB: File format is pdf, png, jpeg, jpg and maximal sized 1 MB<br><br>' ?>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

