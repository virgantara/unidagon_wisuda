<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\helpers\MyHelper;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model app\models\Penghargaan */
/* @var $form yii\widgets\ActiveForm */

$listKegiatan1 = MyHelper::convertKategoriKegiatan('1408');
// print_r($listKegiatan1);exit;
$listKegiatan2 = MyHelper::convertKategoriKegiatan('1410');

$listKegiatan = array_merge($listKegiatan1, $listKegiatan2);

$list_tingkat = ArrayHelper::map(\app\models\TingkatPenghargaan::find()->all(),'id','nama');
$list_jenis = ArrayHelper::map(\app\models\JenisPenghargaan::find()->all(),'id','nama');

$query = \app\models\KomponenKegiatan::find();
$query->alias('p');
$query->select(['p.nama']);
$query->joinWith(['unsur as u']);
$query->where([
  'u.kode' => 'PENUNJANG'
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

<div class="penghargaan-form">

    <?php $form = ActiveForm::begin(); ?>
     <?= $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?>    
      <?php 
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
      echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
    }
    ?>
    <?= $form->field($model, 'komponen_kegiatan_id',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $listKomponenKegiatan,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Komponen Kegiatan -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>
     <?= $form->field($model, 'kategori_kegiatan_id',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $listKegiatan,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Kategori Kegiatan -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>
    
    <?= $form->field($model, 'id_tingkat_penghargaan',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $list_tingkat,

            'options'=>['placeholder'=>Yii::t('app','- Pilih -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>

    <?= $form->field($model, 'id_jenis_penghargaan',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $list_jenis,

            'options'=>['placeholder'=>Yii::t('app','- Pilih -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>
        <?= $form->field($model, 'bentuk',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>
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
        )?>

    

    <?= $form->field($model, 'pemberi',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'f_penghargaan')->widget(FileInput::classname(), [
                'options' => ['accept' => ''],
                'pluginOptions' => [
                    'showUpload' => false,
                ]
            ]).'NB: File format is pdf and max size 1 MB<br><br>' ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
