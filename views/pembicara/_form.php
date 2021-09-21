<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;


$listKegiatan = \app\helpers\MyHelper::convertKategoriKegiatan('1303');


$list_capaian_luaran = ArrayHelper::map(\app\models\CapaianLuaran::find()->all(),'id','nama');
$list_kategori_pembicara = ArrayHelper::map(\app\models\KategoriPembicara::find()->all(),'id','nama');
/* @var $this yii\web\View */
/* @var $model app\models\Pembicara */
/* @var $form yii\widgets\ActiveForm */


$query = \app\models\KomponenKegiatan::find();
$query->alias('p');
$query->select(['p.nama']);
$query->joinWith(['unsur as u']);
$query->where([
  'u.kode' => 'ABDIMAS'
]);
$query->groupBy(['p.nama']);
$query->orderBy(['p.nama'=>SORT_ASC]);

$listKomponen = $query->all();
$listKomponenKegiatan = [];

foreach($listKomponen as $k)
{
    $list = \app\models\KomponenKegiatan::find()->where(['nama'=>$k->nama])->all();
   
    $tmp = [];
    foreach($list as $item)
    {
        $tmp[$item->id] = $item->subunsur.' - AK: '.$item->angka_kredit;
    }

    $listKomponenKegiatan[$k->nama] = $tmp;
}
?>

<div class="pembicara-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?>  
    <?= $form->field($model, 'id_pembicara',['options' => ['tag' => false]])->hiddenInput(['class'=>'form-control','maxlength' => true])->label(false) ?>

     <?= $form->field($model, 'id_kategori_capaian_luaran',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $list_capaian_luaran,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Capaian Luaran -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>
    <?= $form->field($model, 'id_kategori_kegiatan',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $listKegiatan,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Kategori Kegiatan -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>
     <?= $form->field($model, 'komponen_kegiatan_id',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $listKomponenKegiatan,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Komponen Kegiatan -')],
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

  

    <?= $form->field($model, 'judul_makalah',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

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
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
