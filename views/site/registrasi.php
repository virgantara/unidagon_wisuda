<?php
use app\helpers\MyHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */

$this->title = 'Pendaftaran Wisuda';

?>

<style>
    .swal2-content{
        text-align: left;
    }

    .well{
        background-color: #dbdbdb;
        border-color: darkgray;
    }
</style>

<div class="hero-mini">
        
    
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 pr-lg-5">
                <h1>Graduation Registration</h1>
                <p class="lead">Before registration, make sure your data is valid. <br>You can begin your registration by entering your NIM below</p>
                <div class="cta">
                    <?php $form = ActiveForm::begin([
                            'id' => 'form_registrasi'
                        ]); ?>
                         <?php 
                        foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                          echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
                        }
                        ?>
                        <?= $form->errorSummary($model,['header'=>'<div class="alert alert-danger">','footer'=>'</div>']);?>  
                            
                                <?= $form->field($model, 'username',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true,'placeholder' => 'Enter your NIM...','autocomplete' => 'off','id'=>'nim'])->label(false) ?>
                                <br>
                                <button id="btn-submit" class="btn btn-lg btn-info btn-icon icon-left" type="submit"><i class="fa fa-search"></i> Search NIM</button> <input class="btn btn-lg btn-danger btn-icon icon-left" type="reset" value="Reset">
                              
                        <?php ActiveForm::end(); ?>                   
                </div>
            </div>
            <div class="col-lg-4 d-lg-block d-none">
                <img src="https://getstisla.com/landing/undraw_post_online_dkuk.svg" alt="image" class="img-fluid img-flip" width="80%">
            </div>
        </div>
    </div>
</div>

<section class="download-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-12">
                <p>
                <?php 
                if(empty($setting)){
                ?>
                <div class="alert alert-danger">
                    Oops, Mohon maaf. Saat ini belum ada informasi tentang wisuda.
                </div>
                <?php }
                else{
                    echo "<div class='well'>";
                    echo $setting->kode_setting;
                    echo $setting->konten;
                    echo "</div>";
                }
                 ?>
                </p>

            </div>
            
        </div>
    </div>
</section>
    
    
    
<?php

$this->registerJs('

$(document).on("click","#btn-submit",function(e){
    e.preventDefault()

    var obj = new Object;
    obj.nim = $("#nim").val();
    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['peserta/ajax-cek-siakad']).'\',
        async: true,
        beforeSend : function(){
            Swal.showLoading()
        },
        error: function(e){
            Swal.close()
            Swal.fire({
                title: \'Oops!\',
                icon: \'error\',
                text: e.responseText
            });
        },
        success: function(res){
            Swal.close()
            var res = $.parseJSON(res);
            // console.log(res)
            if(res.code == 200){
                var raw = "<h2>Your profile</h2>"

                raw += "<div class=\'table-responsive\'>"
                raw += "<div class=\'col-lg-3 col-md-12 col-sm-12 col-xs-12\'>"
                raw += "<img width=\'200px\' src=\'"+res.items.foto_path+"\' alt=\'Foto Profil\'>"
                raw += "</div>"
                raw += "<div class=\'col-lg-9\'>"
                raw += "<table class=\'table table-hover\'>"
                raw += "<tr>"
                raw += "<td>Name</td>"
                raw += "<td>: "+res.items.nama_mahasiswa+"</td>"
                raw += "</tr>"
                raw += "<tr>"
                raw += "<td>NIM</td>"
                raw += "<td>: "+res.items.nim_mhs+"</td>"
                raw += "</tr>"
                raw += "<tr>"
                raw += "<td>Date of birth</td>"
                raw += "<td>: "+res.items.tgl_lahir+"</td>"
                raw += "</tr>"
                raw += "<tr>"
                raw += "<td>Prodi</td>"
                raw += "<td>: "+res.items.nama_prodi+"</td>"
                raw += "</tr>"
                raw += "<tr>"
                raw += "<td>Fakultas</td>"
                raw += "<td>: "+res.items.nama_fakultas+"</td>"
                raw += "</tr>"

                raw += "</table>"
                raw += "</div>"
                raw += "</div>"

                Swal.fire({
                    width: \'64em\',
                    title: \'Proceed to Registration?\',
                    icon: \'success\',
                    html: raw,
                    showCancelButton: true,
                    confirmButtonColor: \'#3085d6\',
                    cancelButtonColor: \'#d33\',
                    confirmButtonText: \'Yes, proceed!\'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#form_registrasi").submit()
                    }
                })
                
            }

            else{
                Swal.fire({
                    title: \'Oops!\',
                    icon: \'error\',
                    text: res.message
                });
            }
                
        }
            
            

    });
    // Swal.fire({
    //   title: \'Apakah Anda yakin ingin menghapus data ini?\',
    //   icon: \'warning\',
    //   showCancelButton: true,
    //   confirmButtonColor: \'#3085d6\',
    //   cancelButtonColor: \'#d33\',
    //   confirmButtonText: \'Ya, hapus sekarang!\'
    // }).then((result) => {
    //     if (result.isConfirmed) {
    //         $.ajax({
    //             type : \'POST\',
    //             data : {
    //                 dataPost : obj
    //             },
    //             url : \''.Url::to(['bkd-dosen/ajax-remove']).'\',
    //             async: true,
    //             beforeSend : function(){

    //             },
    //             success: function(res){
    //                 var res = $.parseJSON(res);
    //                 if(res.code == 200)
    //                     $("#ganti-periode").trigger("change")
    //                 else{
    //                     Swal.fire({
    //                       title: \'Oops!\',
    //                       icon: \'error\',
    //                       text: res.message
    //                     });
    //                 }
                        
    //             }
                    
                    

    //         });
    //     }   
    // })

    

})

', \yii\web\View::POS_READY);

?>
