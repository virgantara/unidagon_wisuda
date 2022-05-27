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
                            
                                <?= $form->field($model, 'nim',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true,'placeholder' => 'Enter your NIM...','autocomplete' => 'off','id'=>'nim'])->label(false) ?>
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
<section>
    <div class="container">
        <p>
<?php 
if(empty($periode)){
?>
<div class="alert alert-danger">
    Oops, Mohon maaf. Saat ini belum ada pembukaan pendaftaran wisuda.
</div>
<?php }
else{

    setlocale(LC_ALL, 'id_ID', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');


    $tgl_awal = strftime('%A, %d %B %Y',strtotime($periode->tanggal_buka));
    $tgl_akhir = strftime('%A, %d %B %Y',strtotime($periode->tanggal_tutup));
    echo "<h1>Pembukaan Pendaftaran ".$periode->nama_periode."</h1>";
    echo "<div class='alert alert-info'>";
    echo "Periode Pendaftaran ".$periode->nama_periode." dibuka pada ";
    echo "<b>".$tgl_awal.' s/d '.$tgl_akhir."</b>";
    echo "</div>";
}
 ?>
</p>
<div class="panel">
    <div class="panel-body">
        
        
    <div class="custom-tabs-line tabs-line-bottom left-aligned">
        <ul class="nav" role="tablist">
            <li class="active"><a href="#tab-bottom-left1" role="tab" data-toggle="tab">Overview</a></li>
            <li><a href="#tab-bottom-left2" role="tab" data-toggle="tab">Maklumat <span class="badge bg-danger">new</span></a></li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="tab-bottom-left1">
            <div class="row">
                <div class="col-md-6">
                    <div class="metric">
                        <span class="icon"><i class="fa fa-users"></i></span>
                        <p><img style="display: none;" id="loading1" src="<?=Yii::getAlias('@klorofil');?>/assets/img/loading.gif" alt="Loading">
                            <span class="number" id="total_wisudawan">0</span>
                            <span class="title">Total Calon Wisudawan</span>

                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="metric">
                        <span class="icon"><i class="fa fa-check"></i></span>
                        <p><img style="display: none;" id="loading2" src="<?=Yii::getAlias('@klorofil');?>/assets/img/loading.gif" alt="Loading">
                            <span class="number" id="total_valid">0</span>
                            <span class="title">Data Valid</span>
                        </p>
                    </div>
                </div>
                
            </div>
        </div>
        <div class="tab-pane fade" id="tab-bottom-left2">
            <?php 
                if(empty($setting)){
                ?>
                <div class="alert alert-danger">
                    Oops, Mohon maaf. Saat ini belum ada informasi tentang wisuda.
                </div>
                <?php }
                else{

                    // echo "<h1 class='text-center'>".$setting->kode_setting."</h1>";
                    echo "<div class='well'>";
                    
                    echo $setting->konten;
                    echo "</div>";
                }
            ?>
        </div>
    </div>
    </div>
</div>
    </div>
</section>
    
    
<?php

$this->registerJs('


getTotal()
getBelumLengkap()

function getTotal(){
    $.ajax({
        type : \'POST\',
        url : \''.Url::to(['peserta/ajax-total-wisudawan']).'\',
        async: true,
        beforeSend : function(){
            $("#loading1").show()
            $("#loading2").show()
            $("#loading3").show()
        },
        error: function(e){
            Swal.fire({
                title: \'Oops!\',
                icon: \'error\',
                text: e.responseText
            });
            $("#loading1").hide()
            $("#loading2").hide()
            $("#loading3").hide()
        },
        success: function(data){
            $("#loading1").hide()
            $("#loading2").hide()
            $("#loading3").hide()
            var res = $.parseJSON(data);
              
            if(res.code == 200){
                
                $("#total_wisudawan").html(res.total_wisudawan)
                $("#total_valid").html(res.total_valid)
                $("#total_invalid").html(res.total_invalid)
            }else{
                Swal.fire({
                    title: \'Oops!\',
                    icon: \'error\',
                    text: res.message
                });
            }
        }
    })
}


function getBelumLengkap(){
    $.ajax({
        type : \'POST\',
        url : \''.Url::to(['peserta/ajax-total-belum-lengkap']).'\',
        async: true,
        beforeSend : function(){
            $("#loading4").show()
        },
        error: function(e){
            Swal.fire({
                title: \'Oops!\',
                icon: \'error\',
                text: e.responseText
            });
            $("#loading1").hide()
        },
        success: function(data){
            $("#loading4").hide()
            var res = $.parseJSON(data);
            
            if(res.code == 200){
                // Swal.close()
                $("#total_belum_lengkap").html(res.total)
            }else{
                Swal.fire({
                    title: \'Oops!\',
                    icon: \'error\',
                    text: res.message
                });
            }
        }
    })
}

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
            Swal.fire({
                title: \'Oops!\',
                icon: \'error\',
                text: e.responseText
            });
        },
        success: function(res){
            // Swal.hideLoading()
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
                raw += "<tr>"
                raw += "<td>E-mail</td>"
                raw += "<td>: "+res.items.email+"<br><small style=\'color:red\'>Your WISUDA Account will be sent to this email. You can change your email from your SIAKAD > My Profile > Update Profile</small></td>"
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
                    confirmButtonText: \'Yes, proceed!\',
                    showLoaderOnConfirm: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        var obj = new Object;
                        obj.nim = $("#nim").val();
                        $.ajax({
                            type : \'POST\',
                            data : {
                                dataPost : obj
                            },
                            url : \''.Url::to(['peserta/ajax-proceed']).'\',
                            async: true,
                            beforeSend : function(){
                                Swal.fire({
                                    title : "Please wait",
                                    html: "Processing your request",
                                    allowOutsideClick: false,
                                    showConfirmButton : false,
                                    onBeforeOpen: () => {
                                        Swal.showLoading()
                                    },
                                    
                                })
                            },
                            error: function(e){
                                Swal.fire({
                                    title: \'Oops!\',
                                    icon: \'error\',
                                    text: e.responseText
                                });
                            },
                            success: function(data){
                                
                                var res = $.parseJSON(data);
                                if(res.code == 200){
                                    Swal.fire({
                                        title: \'Yeay!\',
                                        icon: \'success\',
                                        text: res.message
                                    }); 
                                }else{
                                    Swal.fire({
                                        title: \'Oops!\',
                                        icon: \'error\',
                                        text: res.message
                                    });
                                }
                            }
                        })
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
