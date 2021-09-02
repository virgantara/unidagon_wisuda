<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\models\Buku */
/* @var $form yii\widgets\ActiveForm */

$listPublikasi = ArrayHelper::map(\app\models\JenisLuaran::find()->where(['keyword' => 'buku'])->all(),'id','nama');

$list_jenis_bahan_ajar = ArrayHelper::map(\app\models\JenisBahanAjar::find()->orderBy(['nama'=>SORT_ASC])->all(),'nama','nama');
$list_capaian_luaran = ArrayHelper::map(\app\models\CapaianLuaran::find()->all(),'nama','nama');
?>
<?php $form = ActiveForm::begin(); ?>
<div class="col-lg-6">

    <?= $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?>    

    <?= $form->field($model, 'jenis_luaran_id')->dropDownList($listPublikasi, ['prompt'=>'..Pilih Jenis Luaran..']);?>
    <?= $form->field($model, 'id_jenis_bahan_ajar')->dropDownList($list_jenis_bahan_ajar, ['prompt'=>'..Pilih Jenis Bahan Ajar..']);?>
    <?= $form->field($model, 'id_kategori_capaian_luaran')->dropDownList($list_capaian_luaran, ['prompt'=>'..Pilih Jenis Capaian Luaran..']);?>
    <?= $form->field($model, 'tahun')->textInput() ?>
    <?= $form->field($model, 'tanggal_terbit')->widget(
        DatePicker::className(),[
            'name' => 'tanggal', 
            'value' => date('d-m-Y', strtotime('0 days')),
            'options' => ['placeholder' => 'Pilih tanggal terbit ...'],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'todayHighlight' => true
            ]
        ]
    ) ?>
    <?= $form->field($model, 'judul')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'penerbit')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'vol')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'ISBN')->textInput(['maxlength' => true]) ?>
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

     <?= $form->field($model, 'f_karya')->widget(FileInput::classname(), [
                'options' => ['accept' => ''],
                'pluginOptions' => [
                    'showUpload' => false,
                ]
            ])->label(false).'NB: File format is pdf and max size 3 MB<br><br>' ?>
 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

   

</div>
<div class="col-lg-6">
    <?php 
    if(!$model->isNewRecord)
    {
        foreach($model->bukuAuthors as $dsn)
        {
    ?>
    <div class="row form-group">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label class="numbering">Author</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
            <div class="input-group">
                <div class="form-line">
                    <input placeholder="Type a name of author" type="text" class="form-control date nama_author" name="authors[]" autocomplete="off" value="<?=$dsn->author->dataDiri->nama;?>"/>
                     <input type="hidden" class="nama_author_id" name="author_id[]" value="<?=$dsn->NIY;?>"/>

                </div>
                <span class="input-group-addon"><a href="javascript:void(0)" class="btn-clear"><i class="fa fa-trash"></i></a></span>
            </div>
        </div>
    </div>
    <?php
        }
    }

    else{

        
    ?>
    <div class="row form-group">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label class="numbering">Author</label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
            <div class="input-group">
                <div class="form-line">
                    <input placeholder="Type a name of author" type="text" class="form-control nama_author" name="authors[]" autocomplete="off" value=""/>
                     <input type="hidden" class="nama_author_id" name="author_id[]"/>

                </div>
                <span class="input-group-addon"><a href="javascript:void(0)" class="btn-clear"><i class="fa fa-trash"></i></a></span>
            </div>
        </div>
    </div>
    <?php
    }
    ?>

    <div class="row clearfix">
        
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label></label>
        </div>
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
            <a title="Add new author" href="javascript:void(0)" id="btn-add" class="btn btn-info"><i class="fa fa-plus"></i> Add new author</a>
        </div>
    </div>
    
    
</div>
 <?php ActiveForm::end(); ?>


<?php

$this->registerJs(' 

    function addAuthor(selector){
        var html = \'<div class="row form-group">\';
                html += \'<div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">\';
                html += \'    <label class="numbering">Author</label>\';
                html += \'</div>\';
                html += \'<div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">\';
                    html += \'<div class="input-group">\';
                        html += \'<div class="form-line">\';
                        html += \'    <input placeholder="Type a name of author" type="text" class="form-control nama_author" name="authors[]" autocomplete="off" value=""/>\';
                       html += \'      <input type="hidden" class="nama_author_id" name="author_id[]"/>\';

                        html += \'</div>\';
                        html += \'<span class="input-group-addon"><a href="javascript:void(0)" class="btn-clear"><i class="fa fa-trash"></i> </a></span>\';
                    html += \'</div>\';
                    
                html += \'</div>\';
            html += \'</div>\';

            $(selector).parent().parent().before(html);
    }

    function authorNumbering(){
        var i = 1;
        $(\'label.numbering\').each(function(){
          $(this).html("Author " +i);
          i++;
         
        });
    }

 
    $(document).bind("keyup.autocomplete",function(){

        $(\'.nama_author\').autocomplete({
            minLength:1,
                select:function(event, ui){
                $(this).next().val(ui.item.id);

            },
            focus: function (event, ui) {
                $(this).next().val(ui.item.id);

            },
            source:function(request, response) {
                $.ajax({
                    url: "'.Url::to(['dosen/ajax-cari-dosen']).'",
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

    authorNumbering();
    $(document).on("click","#btn-add",function(e){
        e.preventDefault();

        addAuthor(this);

        authorNumbering();
    });

    $(document).on("click",".btn-clear",function(e){
        e.preventDefault();
        $(this).parent().parent().parent().parent().remove();
        // $(this).parent().prev().find(".nama_author_id").val("");
        // $(this).parent().prev().find(".nama_author").val("");
        authorNumbering();
    });
  
    


    ', \yii\web\View::POS_READY);

?>