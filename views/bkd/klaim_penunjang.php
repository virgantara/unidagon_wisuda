<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\helpers\MyHelper;
use app\assets\IntroAsset;
IntroAsset::register($this);


$this->title = "Pelaksanaan Penunjang";
$this->params['breadcrumbs'][] = $this->title;
$list_status = \app\helpers\MyHelper::getListStatusBKD();
$list_status_color = \app\helpers\MyHelper::getListStatusBKDColor();
?>
<h1><?=$this->title;?></h1>

<ul class="nav nav-tabs">
    <li role="presentation" class="">
      <a href="<?=Url::to(['bkd/klaim','step'=>1]);?>"   >Biodata</a>
    </li>
    <li role="presentation" class="">
      <a href="<?=Url::to(['bkd/klaim','step'=>2]);?>"   >Pelaksanaan Pendidikan</a>
    </li>
    <li role="presentation" class="">
      <a href="<?=Url::to(['bkd/klaim','step'=>3]);?>"  >Pelaksanaan Penelitian</a>
    </li>
    <li role="presentation" class="">
      <a href="<?=Url::to(['bkd/klaim','step'=>4]);?>"  >Pelaksanaan Pengabdian</a>
    </li>
    <li role="presentation" class="active">
      <a href="<?=Url::to(['bkd/klaim','step'=>5]);?>"  >Pelaksanaan Penunjang</a>
    </li>
    <li role="presentation" class="">
      <a href="<?=Url::to(['bkd/klaim','step'=>6]);?>"  >Simpulan</a>
    </li>
</ul>
<?php 
foreach($list_komponen_utama as $komponen_utama):
 ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="pull-left"><?=$komponen_utama['nama'];?></div>
                <div class="pull-right"><a href="javascript:void(0)" data-item="<?=$komponen_utama['nama'];?>" class="btn btn-primary btn_tarik_penunjang"><i class="fa fa-refresh"></i> Tarik</a></div>
            </div>
            <div class="panel-body">
                <table class="table" id="tabel-penunjang-<?=$komponen_utama['nama'];?>">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="55%">Nama Kegiatan</th>
                            <th width="15%">Status</th>
                            <th width="15%">Beban Tugas</th>
                            <th width="10%">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                        foreach($list_komponen[$komponen_utama['nama']] as $komponen):

                            if(!empty($results[$komponen->id])):
                                foreach($results[$komponen->id] as $q => $item): 

                                    $color = $list_status_color[$item['status_bkd']];
                        ?>
                        <tr>
                            <td><?=$q+1;?></td>
                            <td><?=$item['deskripsi'];?> - <b><?=$item['subunsur'];?></b></td>
                            
                            <td>

                                <div class="btn-group">
                                  <button type="button" class="btn btn-<?=$color;?>  dropdown-toggle" data-toggle="dropdown">
                                    <?=(!empty($list_status[$item['status_bkd']]) ? $list_status[$item['status_bkd']] : null);?> <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu" role="menu">
                                    <?php foreach($list_status as $q=>$status): ?>
                                    <li><a href="#" data-item="<?=$q;?>" class="btn_ubah_status_bkd" data-key="<?=$item['id'];?>"><?=$status;?></a></li>
                                    <?php endforeach ?>
                                  </ul>
                                </div>
                            </td>
                            <td><?=$item['sks'];?></td>
                            <td><a href='javascript:void(0)' data-item='<?=$item['id'];?>' class='remove_bkd'><i class='fa fa-trash'></i></a></td>
                        </tr>
                        <?php 
                                endforeach; 
                            endif;
                        endforeach; 
                        ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>
<?php endforeach ?>
<?php 

$this->registerJs(' 



$(document).on("click",".btn_ubah_status_bkd",function(e){
    e.preventDefault()

    var obj = new Object
    obj.id = $(this).data("key")
    obj.status_bkd = $(this).data("item");

    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['bkd-dosen/ajax-update-status']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){

            var res = $.parseJSON(res);
            if(res.code == 200){
                Swal.fire({
                  title: \'Yeay!\',
                  icon: \'success\',
                  text: res.message
                }).then(res=>{
                    window.location.reload();
                    
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

})


$(document).on("click",".btn_tarik_penunjang",function(e){
    e.preventDefault()

    var obj = new Object
    obj.nama_komponen_kegiatan = $(this).data("item")
    obj.is_claimed = "0";
    obj.tahun_id = $("#ganti-periode").val()
    if($(this).is(":checked"))
        obj.is_claimed = "1"

    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['skp-item/ajax-claim-penunjang']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){

            var res = $.parseJSON(res);
            if(res.code == 200){
                Swal.fire({
                  title: \'Yeay!\',
                  icon: \'success\',
                  text: res.message
                }).then(res=>{
                    window.location.reload()
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

})


$(document).on("click",".remove_bkd",function(e){
    e.preventDefault()

    var obj = new Object;
    obj.id = $(this).data("item");
    
    Swal.fire({
      title: \'Apakah Anda yakin ingin menghapus data ini?\',
      icon: \'warning\',
      showCancelButton: true,
      confirmButtonColor: \'#3085d6\',
      cancelButtonColor: \'#d33\',
      confirmButtonText: \'Ya, hapus sekarang!\'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type : \'POST\',
                data : {
                    dataPost : obj
                },
                url : \''.Url::to(['bkd-dosen/ajax-remove']).'\',
                async: true,
                beforeSend : function(){

                },
                success: function(res){
                    var res = $.parseJSON(res);
                    if(res.code == 200){

                        window.location.reload();
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
        }   
    })

    

})

var introguide = introJs();
introguide.setOptions({
    exitOnOverlayClick: false,
    steps : [
        {
            intro: "Fitur ini berisi kegiatan apapun yang akan diklaim sebagai BKD",
            title: "Fitur Klaim BKD",
            element : "h1"
        },
        {
            intro: "Periode BKD yang berjalan. Periode ini hanya bisa diubah oleh bagian Admin Biro SDM",
            title: "Pilihan Periode",
            element : "#ganti-periode"
        },
        {
            intro: "Data ini bersumber dari SIAKAD pada tahun periode BKD",
            title: "Data Pengajaran",
            element : "#tabel-pengajaran"
        },
        {
            intro: "Anda bisa klaim kegiatan dengan cara mencentang checkbox di bawah ini",
            title: "Checkbox Klaim",
            element : ".klaim"
        },
        {
            intro: "Data ini bersumber dari SISTER atau Input manual",
            title: "Data Penelitian & Publikasi",
            element : "#tabel-publikasi"
        },
        {
            intro: "Data ini bersumber dari SISTER atau Input manual",
            title: "Data Pengabdian",
            element : "#tabel-pengabdian"
        },
        {
            intro: "Data ini bersumber dari SISTER atau Input manual",
            title: "Data Penunjang",
            element : "#tabel-penunjang"
        },
        
    ]
});

var doneTour = localStorage.getItem(\'evt_klaim_bkd\') === \'Completed\';

if(!doneTour) {
    introguide.start()

    introguide.oncomplete(function () {
        localStorage.setItem(\'evt_klaim_bkd\', \'Completed\');
        Swal.fire({
          title: \'Ulangi Langkah Fitur ini ?\',
          text: "",
          icon: \'warning\',
          showCancelButton: true,
          width:\'35%\',
          confirmButtonColor: \'#3085d6\',
          cancelButtonColor: \'#d33\',
          confirmButtonText: \'Ya, ulangi lagi!\',
          cancelButtonText: \'Tidak, sudah cukup\'
        }).then((result) => {
          if (result.value) {
            introguide.start();
            localStorage.removeItem(\'evt_klaim_bkd\');
          }

        });
    });

}


function getPengabdian(tahun,komponen_kegiatan_id){
    
    var obj = new Object;
    obj.tahun = tahun;
    obj.komponen_kegiatan_id = komponen_kegiatan_id
    var counter = 0;
    $("#tabel-penunjang-"+obj.komponen_kegiatan_id+" > tbody").empty()
    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['bkd-dosen/ajax-list-penunjang']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
            var row = ""
            
            var total_sks = 0
            $.each(res, function(i,obj){
                counter++;

                row += "<tr>"
                row += "<td>"+(counter)+"</td>"
                row += "<td>"+obj.nama_kegiatan+"</td>"
                row += "<td>"+obj.status_bkd+"</td>"
                row += "<td>"+obj.sks_bkd+"</td>"
                row += "<td><a href=\'javascript:void(0)\' data-item=\'"+obj.id+"\' class=\'remove_bkd\'><i class=\'fa fa-trash\'></i></a></td>"
                row += "</tr>"

            })

            $("#tabel-penunjang-"+obj.komponen_kegiatan_id+" > tbody").append(row)
                
        }

    });

    
}




', \yii\web\View::POS_READY);

?>