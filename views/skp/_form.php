<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$list_periode = ArrayHelper::map(\app\models\BkdPeriode::find()->orderBy(['tahun_id' => SORT_DESC])->all(),'tahun_id','nama_periode');
/* @var $this yii\web\View */
/* @var $model app\models\Skp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="skp-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'periode_id',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $list_periode,

            'options'=>['placeholder'=>Yii::t('app','- Pilih -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>
  
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

