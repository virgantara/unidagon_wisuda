<?php
use app\helpers\MyHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\HighchartAsset;

HighchartAsset::register($this);

/* @var $this yii\web\View */

$this->title = 'Beranda';

?>



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
                <div class="col-md-3">
                    <div class="metric">
                        <span class="icon"><i class="fa fa-users"></i></span>
                        <p><img style="display: none;" id="loading1" src="<?=Yii::getAlias('@klorofil');?>/assets/img/loading.gif" alt="Loading">
                            <a href="<?=Url::to(['peserta/index'])?>"><span class="number" id="total_wisudawan">0</span></a>
                            <span class="title">Total Calon Wisudawan</span>

                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric">
                        <span class="icon"><i class="fa fa-check"></i></span>
                        <p><img style="display: none;" id="loading2" src="<?=Yii::getAlias('@klorofil');?>/assets/img/loading.gif" alt="Loading">
                            <span class="number" id="total_valid">0</span>
                            <span class="title">Data Valid</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric">
                        <span class="icon"><i class="fa fa-ban"></i></span>
                        <p><img style="display: none;" id="loading3" src="<?=Yii::getAlias('@klorofil');?>/assets/img/loading.gif" alt="Loading">
                            <span class="number" id="total_invalid">0</span>
                            <span class="title">Data Belum Valid</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric">
                        <span class="icon"><i class="fa fa-bar-chart"></i></span>
                        <p><img style="display: none;" id="loading4" src="<?=Yii::getAlias('@klorofil');?>/assets/img/loading.gif" alt="Loading">
                            <span class="number" id="total_belum_lengkap">0</span>
                            <span class="title">Total Bukti Belum Lengkap</span>
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


', \yii\web\View::POS_READY);

?>
