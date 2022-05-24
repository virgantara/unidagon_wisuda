<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
/* @var $this yii\web\View */
/* @var $model common\models\Jabatan */
/* @var $form yii\widgets\ActiveForm */
$listJabatan = ArrayHelper::map(\app\models\MJabatan::find()->orderBy(['nama'=>SORT_ASC])->all(),'id','nama');
$listUnit = ArrayHelper::map(\app\models\UnitKerja::find()->orderBy(['nama'=>SORT_ASC])->all(),'id','nama');

$nama_dosen = '';
$unsur_utama_id = null;
if(!$model->isNewRecord){
    $nama_dosen = $model->nIY->dataDiri->nama;
    $unsur_utama_id = !empty($model->komponenKegiatan) ? $model->komponenKegiatan->unsur_id : null;
}



$list_unsur = ArrayHelper::map(\app\models\UnsurUtama::find()->orderBy(['urutan'=>SORT_ASC])->all(),'id','nama');

?>

<div class="jabatan-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?php 
    if(!$model->isNewRecord){
        echo $nama_dosen;
    }
    ?>
    
    
    <?= $form->field($model, 'NIY',['options'=>['tag' => false]])->hiddenInput(['id'=>'dosen_id'])->label(false) ?>
   
    <?= $form->field($model, 'unker_id')->widget(Select2::classname(),[
        'data' => $listUnit,
        'options'=>['placeholder'=>Yii::t('app','- Pilih Satuan Kerja -')],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>
    <?= $form->field($model, 'jabatan_id')->widget(Select2::classname(),[
        'data' => $listJabatan,
        'options'=>['placeholder'=>Yii::t('app','- Pilih Jabatan -')],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>
    
    <div class="form-group">
        <label for="">Unsur Utama</label>
        <?= Select2::widget([
        'name' => 'unsur_utama',
        'data' => $list_unsur,
        'value' => $unsur_utama_id,
        'options'=>['id'=>'unsur_utama','placeholder'=>Yii::t('app','- Pilih -')],
        'pluginOptions' => [
            'allowClear' => true,
            'initialize' => true,
        ],
    ])?>
    </div>
    

    <?php 
        echo $form->field($model, 'komponen_kegiatan_id')->widget(DepDrop::classname(),[
            'type'=>DepDrop::TYPE_SELECT2,
            'options'=>['id'=>'komponen_kegiatan_id'],
            'select2Options'=>[
                'pluginOptions'=>[
                    'allowClear'=>true,
                ]
            ],
            'pluginOptions'=>[
                'depends'=>['unsur_utama'],
                'initialize' => true,
                'placeholder'=>'- Pilih -',
                'url'=>\yii\helpers\Url::to(['/komponen-kegiatan/subkomponen'])
            ]
            
        ]);
        ?>
    

    <?= $form->field($model, 'no_sk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_awal')->widget(
        DatePicker::className(),[
            'name' => 'tanggal', 
            'value' => date('d-m-Y', strtotime('0 days')),
            'options' => ['placeholder' => 'Pilih tanggal awal ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                'autoclose' => true
            ]
        ]
    ) ?>

    <?= $form->field($model, 'tanggal_akhir')->widget(
        DatePicker::className(),[
            'name' => 'tanggal', 
            'value' => date('d-m-Y', strtotime('0 days')),
            'options' => ['placeholder' => 'Pilih tanggal akhir ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true,
                'autoclose' => true
            ]
        ]
    ) ?>

    <?= $form->field($model, 'f_penugasan')->fileInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php

$this->registerJs(' 
 

$(document).bind("keyup.autocomplete",function(){
    $(".nama_dosen").autocomplete({
        minLength:1,
        select:function(event, ui){
       
            $(this).parent().find("#dosen_id").val(ui.item.niy);
                
        },
      
        focus: function (event, ui) {
            $(this).parent().find("#dosen_id").val(ui.item.niy);
        },
        source:function(request, response) {
            $.ajax({
                url: "'.Url::to(["data-diri/ajax-cari-dosen-simpeg"]).'",
                dataType: "json",
                data: {
                    term: request.term,
                    
                },
                success: function (data) {
                    response(data);
                }
            })
        },
       
    });
}); 
', \yii\web\View::POS_READY);

?>
