<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\OrasiIlmiah */
/* @var $form yii\widgets\ActiveForm */


$listKegiatan = \app\helpers\MyHelper::convertKategoriKegiatan('1109');

$list_capaian_luaran = ArrayHelper::map(\app\models\CapaianLuaran::find()->all(),'id','nama');
$list_kategori_pembicara = ArrayHelper::map(\app\models\KategoriPembicara::find()->all(),'id','nama');
$list_tingkat = ArrayHelper::map(\app\models\Tingkat::find()->all(),'id','nama');

?>

<div class="orasi-ilmiah-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?>  
   
    <?= $form->field($model, 'kategori_kegiatan_id',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $listKegiatan,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Kategori Kegiatan -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>

    <?= $form->field($model, 'id_kategori_capaian_luaran',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $list_capaian_luaran,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Capaian Luaran -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>
    
    <?= $form->field($model, 'id_kategori_pembicara',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $list_kategori_pembicara,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Kategori Pembicara -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>

     <?= $form->field($model, 'tingkat_pertemuan_id',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $list_tingkat,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Tingkat Pertemuan -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>
    
    <?= $form->field($model, 'judul_buku_makalah',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'nama_pertemuan_ilmiah',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'penyelenggara_kegiatan',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>
    <?= $form->field($model, 'tanggal_pelaksanaan',['options' => ['tag' => false]])->widget(
            DatePicker::className(),[
                'name' => 'tanggal_pelaksanaan', 
                'value' => date('Y-m-d', strtotime('0 days')),
                'options' => ['placeholder' => 'Pilih tanggal  ...'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]
        );?>
    
    <?= $form->field($model, 'no_sk_tugas',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>
     <?= $form->field($model, 'tanggal_sk_penugasan',['options' => ['tag' => false]])->widget(
            DatePicker::className(),[
                'name' => 'tanggal_sk_penugasan', 
                'value' => date('Y-m-d', strtotime('0 days')),
                'options' => ['placeholder' => 'Pilih tanggal  ...'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]
        );?>

    <?= $form->field($model, 'bahasa',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'file_path')->fileInput().'NB: File format is pdf and max size 2 MB<br><br>' ?>

   
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
