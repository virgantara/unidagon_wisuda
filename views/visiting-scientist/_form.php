<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\VisitingScientist */
/* @var $form yii\widgets\ActiveForm */


$arr= [];
$is_readonly = ['class'=>'form-control','maxlength' => true];
$query = \app\models\KomponenKegiatan::find();
$query->alias('p');
$query->select(['p.nama']);
$query->joinWith(['unsur as u']);
$query->where([
  'u.kode' => 'RISET'
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

$listKegiatan = \app\helpers\MyHelper::convertKategoriKegiatan('1113');
$list_tingkat = ArrayHelper::map(\app\models\Tingkat::find()->all(),'id','nama');

$list_capaian_luaran = ArrayHelper::map(\app\models\CapaianLuaran::find()->all(),'id','nama');
?>

<div class="visiting-scientist-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'id_kategori_capaian_luaran',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $list_capaian_luaran,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Capaian Luaran -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>
    <?= $form->field($model, 'perguruan_tinggi_pengundang',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true,'id'=>'nama_pt','placeholder'=>'Ketik nama Universitas']) ?>

    
     <?= $form->field($model, 'durasi_kegiatan',['options' => ['tag' => false]])->textInput(['type'=>'number']) ?>
      <?= $form->field($model, 'kategori_kegiatan_id',['options' => ['tag' => false]])->widget(Select2::classname(), [
            'data' => $listKegiatan,

            'options'=>['placeholder'=>Yii::t('app','- Pilih Kategori Kegiatan -')],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])?>
    <?= $form->field($model, 'kegiatan_penting_yang_dilakukan',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>
    <?= $form->field($model, 'tingkat',['options' => ['tag' => false]])->widget(Select2::classname(), [
        'data' => $list_tingkat,

        'options'=>['placeholder'=>Yii::t('app','- Pilih Tingkat Pertemuan -')],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])?>
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

   

    <?= $form->field($model, 'durasi',['options' => ['tag' => false]])->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'NIY',['options' => ['tag' => false]])->hiddenInput(['class'=>'form-control','readonly' => 'readonly'])->label(false) ?>

     <?= $form->field($model, 'nama_penelitian_pengabdian',['options' => ['tag' => false]])->hiddenInput(['class'=>'form-control','maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'id_penelitian_pengabdian',['options' => ['tag' => false]])->hiddenInput(['class'=>'form-control','maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'nama_kategori_pencapaian',['options' => ['tag' => false]])->hiddenInput(['class'=>'form-control','maxlength' => true])->label(false) ?>

    

    <?= $form->field($model, 'id_universitas',['options' => ['tag' => false]])->hiddenInput(['class'=>'form-control','maxlength' => true,'id'=>'id_universitas'])->label(false) ?>

    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

$this->registerJs(' 
$(document).bind("keyup.autocomplete",function(){

    $(\'#nama_pt\').autocomplete({
        minLength:3,
        select:function(event, ui){
            $("#id_universitas").val(ui.item.id);

        },
        focus: function (event, ui) {
            $("#id_universitas").val(ui.item.id);

        },
        source:function(request, response) {
            $.ajax({
                url: "/pt/ajax-cari-pt",
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