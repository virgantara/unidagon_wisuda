<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\file\FileInput;
/* @var $this yii\web\View */
/* @var $model app\models\Publikasi */
/* @var $form yii\widgets\ActiveForm */
$arr= [];
$is_readonly = ['class'=>'form-control','maxlength' => true];
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
// $listKomponen = ArrayHelper::map($listKomponen,'id',function($data){
//     return $data->subunsur;
// });
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


$listKegiatan1 = \app\helpers\MyHelper::convertKategoriKegiatan('1401');
$listKegiatan2 = \app\helpers\MyHelper::convertKategoriKegiatan('1402');
$listKegiatan3 = \app\helpers\MyHelper::convertKategoriKegiatan('1404');
$listKegiatan4 = \app\helpers\MyHelper::convertKategoriKegiatan('1405');
$listKegiatan5 = \app\helpers\MyHelper::convertKategoriKegiatan('1407');
$listKegiatan6 = \app\helpers\MyHelper::convertKategoriKegiatan('1411');
$listKegiatan = array_merge($listKegiatan1, $listKegiatan2, $listKegiatan3, $listKegiatan4, $listKegiatan5, $listKegiatan6);

$listJenisPanitia = ArrayHelper::map($list_jenis,'id','nama');
$list_tingkat = ArrayHelper::map(\app\models\Tingkat::find()->all(),'id','nama');

?>
<div class="penunjang-lain-form">

    <?php $form = ActiveForm::begin(); ?>
     <?= $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?>    
      <?php 
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
      echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
    }
    ?>
     <div class="form-group">
        <label class="control-label col-md-3">Kategori Kegiatan*</label>
        <div class="col-md-9">
        <?= $form->field($model, 'kategori_kegiatan_id',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $listKegiatan,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Kategori Kegiatan -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(false)?>
       
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3">Komponen Kegiatan BKD*</label>
        <div class="col-md-9">
        <?= $form->field($model, 'komponen_kegiatan_id',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $listKomponenKegiatan,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Komponen Kegiatan -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(false)?>
       
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Nama Kegiatan *</label>
        <div class="col-md-9">
        <?= $form->field($model, 'nama_kegiatan',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true])->label(false) ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Jenis Kegiatan*</label>
        <div class="col-md-9">
        <?= $form->field($model, 'jenis_panitia_id',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $listJenisPanitia,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Jenis -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(false)?>
       
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Instansi *</label>
        <div class="col-md-9">
        <?= $form->field($model, 'instansi',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true])->label(false) ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Tingkat*</label>
        <div class="col-md-9">
        <?= $form->field($model, 'tingkat_id',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $list_tingkat,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Tingkat -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(false)?>
       
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">No SK Penugasan</label>
        <div class="col-md-9">
        <?= $form->field($model, 'no_sk_tugas',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true])->label(false) ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Tanggal Mulai</label>
        <div class="col-md-9">
        <?= $form->field($model, 'tanggal_mulai',['options' => ['tag' => false]])->widget(
            DatePicker::className(),[
                'name' => 'tanggal_terbit', 
                'value' => date('Y-m-d', strtotime('0 days')),
                'options' => ['placeholder' => 'Pilih tanggal  ...'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]
        )->label(false) ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Tanggal Selesai</label>
        <div class="col-md-9">
         <?= $form->field($model, 'tanggal_selesai',['options' => ['tag' => false]])->widget(
            DatePicker::className(),[
                'name' => 'tanggal_terbit', 
                'value' => date('Y-m-d', strtotime('0 days')),
                'options' => ['placeholder' => 'Pilih tanggal  ...'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true
                ]
            ]
        )->label(false) ?>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">File Bukti</label>
        <div class="col-md-9">
          <?= $form->field($model, 'file_path')->widget(FileInput::classname(), [
                'options' => ['accept' => ''],
                'pluginOptions' => [
                    'showUpload' => false,
                ]
            ])->label(false).'NB: File format is pdf and max size 1 MB<br><br>'  ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
