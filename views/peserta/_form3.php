<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use devgroup\dropzone\DropZone;
/* @var $this yii\web\View */
/* @var $model app\models\Peserta */
/* @var $form yii\widgets\ActiveForm */
?>
<form id="form">
    <ul id="progressbar">
        <li class="" id="step1">
            <strong>Biodata</strong>
        </li>
        <li id="step2"><strong>Data Orang Tua</strong></li>
        <li id="step3" class="active"><strong>Bukti Wisuda</strong></li>
        <li id="step4"><strong>Konfirmasi</strong></li>
    </ul>
    
    
</form>
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="">
        <a href="<?=Url::to(['peserta/create','step'=>1])?>">Biodata</a>
    </li>
    <li role="presentation" class="">
        <a href="<?=Url::to(['peserta/create','step'=>1])?>" >Data Orang Tua</a>
    </li>
    <li role="presentation" class="active">
        <a href="#">Bukti Wisuda</a>
    </li>
    <li role="presentation" class="">
        <a href="<?=Url::to(['peserta/create','step'=>4])?>">Konfirmasi</a>
    </li>
</ul>
<div class="peserta-form">



    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Bukti</th>
                        <th>Unggah</th>
                        <th>Hasil Unggahan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach($list_syarat as $q => $syarat):
                        $ps = $list_bukti_peserta[$syarat->id];
                    ?>
                    <tr>
                        <td><?=$q+1?></td>
                        <td><?=$syarat->nama?></td>
                        <td>
                            
    <?php

echo DropZone::widget(
    [
        'name' => 'file', // input name or 'model' and 'attribute'
        'url' => Url::to(['peserta-syarat/dropzone-upload']), // upload url
        'storedFiles' => [], // stores files
        'eventHandlers' => [
            'sending' => 'function(file, xhr, data) {
                data.append("syarat_id", "'.$syarat->id.'");
            }',
            'success' => 'function(file, data) {
              var hsl = $.parseJSON(data);
              if(hsl.code == 200){
                Swal.fire({
                  title: hsl.message,
                  text: "Reload sekarang?",
                  icon: \'success\',
                  showCancelButton: true,
                  confirmButtonColor: \'#3085d6\',
                  cancelButtonColor: \'#d33\',
                  confirmButtonText: \'Ya, muat ulang laman ini!\'
                }).then((result) => {
                  if (result.value) {
                    location.reload();
                  }
                });
              }

              else{
                Swal.fire({
                  icon: \'error\',
                  title: \'Oops...\',
                  text: hsl.message,
                })
              }
                    

          }'
          ], // dropzone event handlers
        'sortable' => true, // sortable flag
        'sortableOptions' => [], // sortable options
        'htmlOptions' => [], // container html options
        'options' => [], // dropzone js options
    ]
);

 ?>
                        </td>
                        <td>
                            <?php 
                            if(!empty($ps)){
                                echo Html::a('<i class="fa fa-download"></i> Unduh',$ps->file_path,['class'=>'btn btn-success','target'=>'_blank']);
                            }

                             ?>
                            

                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
     <?php $form = ActiveForm::begin(); ?>
    <?= $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?> 
    <?= $form->field($model, 'nim',['options' => ['tag' => false]])->hiddenInput()->label(false) ?>

    <div class="form-group">
        <div class="pull-left">
            <?= Html::a('<i class="fa fa-arrow-left"></i> Prev ',['peserta/create','step'=>2], ['class' => 'btn btn-default']) ?>
        </div>
        <div class="pull-right">
            
            <?= Html::submitButton('Next <i class="fa fa-arrow-right"></i>', ['class' => 'btn btn-success']) ?>    
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
