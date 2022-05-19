<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\helpers\MyHelper;
use app\assets\IntroAsset;
IntroAsset::register($this);

$list_tahun = ArrayHelper::map($list_bkd_periode,'tahun_id','nama_periode');

$session = Yii::$app->session;
$tahun_id = '';
$sd = '';
$ed = '';
$bkd_periode = null;
if($session->has('bkd_periode'))
{
  $tahun_id = $session->get('bkd_periode');
  $bkd_periode = $session->get('bkd_periode_nama');
  $sd = $session->get('tgl_awal');
  $ed = $session->get('tgl_akhir');  
}

$this->title = "Simpulan BKD"
?>
<h1><?=$this->title;?></h1>
<p>
<?php
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'action' => ['bkd/ganti-periode'],
]); ?>


<div class="row">
    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
            <?= Html::dropDownList('tahun',$tahun_id, $list_tahun, ['id' => 'ganti-periode','class'=>'form-control','prompt'=>'- Pilih Periode -']) ?>
            
        </div>
        <div class="form-group">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>

 <?php ActiveForm::end(); ?>
</p>  
<ul class="nav nav-tabs">
    <li role="presentation" class="">
      <a href="<?=Url::to(['bkd/klaim','step'=>1]);?>"   >Pelaksanaan Pendidikan</a>
    </li>
    <li role="presentation" class="">
      <a href="<?=Url::to(['bkd/klaim','step'=>2]);?>"  >Pelaksanaan Penelitian</a>
    </li>
    <li role="presentation" class="">
      <a href="<?=Url::to(['bkd/klaim','step'=>3]);?>"  >Pelaksanaan Pengabdian</a>
    </li>
    <li role="presentation" class="">
      <a href="<?=Url::to(['bkd/klaim','step'=>4]);?>"  >Pelaksanaan Penunjang</a>
    </li>
    <li role="presentation" class="active">
      <a href="<?=Url::to(['bkd/klaim','step'=>5]);?>"  >Simpulan</a>
    </li>
</ul>

<?php 

$this->registerJs(' 


$(document).on("click","#btn_tarik_penunjang",function(e){
    e.preventDefault()

    var obj = new Object
    obj.id = $(this).data("item")
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
            if(res.code == 200)
                $("#ganti-periode").trigger("change")
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
                    if(res.code == 200)
                        $("#ganti-periode").trigger("change")
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

$(document).on("click",".btn-claim-pembicara",function(e){
    e.preventDefault()

    var obj = new Object
    obj.id = $(this).data("item")
    obj.is_claimed = "0";
    obj.tahun_id = $(this).data("ta")
    if($(this).is(":checked"))
        obj.is_claimed = "1"

    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['bkd/ajax-claim-pembicara']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){

            var res = $.parseJSON(res);
            if(res.code == 200)
                $("#ganti-periode").trigger("change")
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

$(document).on("click",".btn-claim-pengelolaJurnal",function(e){
    e.preventDefault()

    var obj = new Object
    obj.id = $(this).data("item")
    obj.is_claimed = "0";
    if($(this).is(":checked"))
        obj.is_claimed = "1"

    obj.tahun_id = $(this).data("ta")


    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['bkd/ajax-claim-pengelola-jurnal']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
            if(res.code == 200)
                $("#ganti-periode").trigger("change")
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

$(document).on("click",".btn-claim-organisasi",function(e){
    e.preventDefault()

    var obj = new Object
    obj.id = $(this).data("item")
    obj.is_claimed = "0";
    if($(this).is(":checked"))
        obj.is_claimed = "1"

    obj.tahun_id = $(this).data("ta")

    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['bkd/ajax-claim-organisasi']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
            if(res.code == 200)
                $("#ganti-periode").trigger("change")
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

$(document).on("click",".btn-claim-penunjang-lain",function(e){
    e.preventDefault()

    var obj = new Object
    obj.id = $(this).data("item")
    obj.is_claimed = "0";
    if($(this).is(":checked"))
        obj.is_claimed = "1"

    obj.tahun_id = $(this).data("ta")

    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['bkd/ajax-claim-penunjang-lain']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
            if(res.code == 200)
                $("#ganti-periode").trigger("change")
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

$(document).on("click",".btn-claim-pengabdian",function(e){
    e.preventDefault()

    var obj = new Object
    obj.id = $(this).data("item")
    obj.tahun_id = $(this).data("ta")
    obj.is_claimed = "0";
    if($(this).is(":checked"))
        obj.is_claimed = "1"

    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['bkd/ajax-claim-pengabdian']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
            if(res.code == 200)
                $("#ganti-periode").trigger("change")
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

$(document).on("click",".btn-claim-penghargaan",function(e){
    e.preventDefault()

    var obj = new Object
    obj.id = $(this).data("item")
    obj.is_claimed = "0";
    if($(this).is(":checked"))
        obj.is_claimed = "1"

    obj.tahun_id = $(this).data("ta")

    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['bkd/ajax-claim-penghargaan']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
            if(res.code == 200)
                $("#ganti-periode").trigger("change")
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

$(document).on("click",".btn-claim-publikasi",function(e){
    e.preventDefault()

    var obj = new Object
    obj.id = $(this).data("item")
    obj.is_claimed = "0";
    if($(this).is(":checked"))
        obj.is_claimed = "1"

    obj.tahun_id = $(this).data("ta")

    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['bkd/ajax-claim-publikasi']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
            if(res.code == 200)
                $("#ganti-periode").trigger("change")
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

$(document).on("click",".btn-claim",function(e){
    e.preventDefault()

    var obj = new Object
    obj.id = $(this).data("item")
    obj.is_claimed = "0";
    if($(this).is(":checked"))
        obj.is_claimed = "1"

    obj.tahun_id = $(this).data("ta");
    obj.sks = $(this).data("sks")

    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['bkd/ajax-claim']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
            if(res.code == 200)
                $("#ganti-periode").trigger("change")
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

function fetchJadwal(tahun, callback){
    let obj = new Object;
    // obj.prodi_id = id;
    obj.tahun = tahun;
    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['pengajaran/ajax-jadwal']).'\',
        async: true,
        beforeSend : function(){
            Swal.showLoading()
        },
        error : function(e){

            Swal.fire({
              title: \'Oops!\',
              icon: \'error\',
              text: e.responseText
            }).then((result) => {
              if (result.value) {
                 
              }
            });
            Swal.hideLoading();
        },
        success: function(res){
            
            var res = $.parseJSON(res);
            if(res)
                callback(null, res)
            else
                callback(res, null)
        }

    });
}

function getPublikasi(tahun){
    
    var obj = new Object;
    obj.tahun = tahun;


    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['publikasi/ajax-list']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
            var row = ""
            $("#tabel-publikasi > tbody").empty()
            var total_sks = 0
            $.each(res, function(i,obj){
                let isClaimed = obj.is_claimed == 1 ? "checked" : "";
                row += "<tr>"
                row += "<td>"+(i+1)+"</td>"
                row += "<td>"+obj.judul_publikasi_paten+"</td>"
                row += "<td>"+obj.nama_jenis_publikasi+"</td>"
                row += "<td>"+obj.tanggal_terbit+"</td>"
                row += "<td><a href=\'"+obj.tautan+"\' target=\'_blank\'>Link</a></td>"
                row += "<td>"+obj.volume+" / "+obj.nomor+" / "+obj.halaman+"</td>"
                row += "<td>"+obj.penerbit+"</td>"
                row += "<td><a href=\'"+obj.doi+"\' target=\'_blank\'>Link</a></td>"
                row += "<td>"+obj.issn+"</td>"
                row += "<td></td>"
                row += "<td><input type=\'checkbox\' "+isClaimed+" data-ta=\'"+tahun+"\' data-item=\'"+obj.id+"\' class=\'btn-claim-publikasi\'/></td>"
                row += "</tr>"

            })

            $("#tabel-publikasi > tbody").append(row)
                
        }

    });
}

function getPengabdian(tahun){
    
    var obj = new Object;
    obj.tahun = tahun;
    var counter = 0;
    $("#tabel-pengabdian > tbody").empty()
    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['pengabdian/ajax-list']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
            var row = ""
            
            var total_sks = 0
            $.each(res, function(i,obj){
                counter++;

                let isClaimed = obj.is_claimed == 1 ? "checked" : "";
                var obj = obj.item
                row += "<tr>"
                row += "<td>"+(counter)+"</td>"
                row += "<td>"+obj.judul_penelitian_pengabdian+"</td>"
                row += "<td>"+obj.nama_skim+"</td>"
                row += "<td>"+obj.tahun_kegiatan+"</td>"
                row += "<td>"+obj.tempat_kegiatan+"</td>"
                row += "<td>"+obj.nilai+"</td>"
                row += "<td><input type=\'checkbox\' "+isClaimed+" data-ta=\'"+$("#ganti-periode").val()+"\' data-item=\'"+obj.ID+"\' class=\'btn-claim-pengabdian\'/></td>"
                row += "</tr>"

            })

            $("#tabel-pengabdian > tbody").append(row)
                
        }

    });

    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['pengelola-jurnal/ajax-list']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
            var row = ""
            
            var total_sks = 0
            $.each(res, function(i,obj){

                let isClaimed = obj.is_claimed == 1 ? "checked" : "";
                counter++;

                var obj = obj.item
                row += "<tr>"
                row += "<td>"+(counter)+"</td>"
                row += "<td>Menjadi "+obj.peran_dalam_kegiatan +" pada "+obj.nama_media_publikasi+"</td>"
                row += "<td></td>"
                row += "<td>"+obj.tgl_sk_tugas+" s/d "+obj.tgl_sk_tugas_selesai+"</td>"
                row += "<td></td>"
                row += "<td>"+obj.sks_bkd+"</td>"
                row += "<td><input type=\'checkbox\' "+isClaimed+" data-ta=\'"+$("#ganti-periode").val()+"\' data-sks=\'"+obj.sks_bkd+"\' data-item=\'"+obj.id+"\' class=\'btn-claim-pengelolaJurnal\'/></td>"
                row += "</tr>"

            })

            $("#tabel-pengabdian > tbody").append(row)
                
        }

    });

    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['pembicara/ajax-list']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
            var row = ""
            
            var total_sks = 0
            $.each(res, function(i,obj){

                let isClaimed = obj.is_claimed == 1 ? "checked" : "";
                counter++;

                row += "<tr>"
                row += "<td>"+(counter)+"</td>"
                row += "<td><b>"+obj.peran_dalam_kegiatan +"</b> dalam <b>"+obj.nama_pertemuan_ilmiah+"</b> oleh "+obj.penyelenggara_kegiatan+" tanggal "+obj.nama_pertemuan_ilmiah+"</td>"
                row += "<td></td>"
                row += "<td>"+obj.tanggal+"</td>"
                row += "<td></td>"
                row += "<td>"+obj.sks_bkd+"</td>"
                row += "<td><input type=\'checkbox\' "+isClaimed+" data-ta=\'"+$("#ganti-periode").val()+"\' data-sks=\'"+obj.sks_bkd+"\' data-item=\'"+obj.id+"\' class=\'btn-claim-pembicara\'/></td>"
                row += "</tr>"

            })

            $("#tabel-pengabdian > tbody").append(row)
                
        }

    });
}



function getPenunjang(tahun){
    $("#tabel-penunjang > tbody").empty()
    var obj = new Object;
    obj.tahun = tahun;
    var counter = 0;
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
            // console.log(res)
            var row = ""
            var total_sks = 0
            $.each(res, function(i,obj){
                let isClaimed = obj.is_claimed == 1 ? "checked" : "";
                counter++;
                row += "<tr>"
                row += "<td>"+(counter)+"</td>"
                row += "<td>"+obj.nama_kegiatan+"</td>"
                row += "<td>"+obj.sks_bkd+"</td>"
                row += "<td><a href=\'javascript:void(0)\' data-item=\'"+obj.id+"\' class=\'remove_bkd\'><i class=\'fa fa-trash\'></i></a></td>"
                row += "</tr>"

            })

            $("#tabel-penunjang > tbody").append(row)
                
        }

    });

   
}


function getJadwal(tahun){
    var obj = new Object
    obj.tahun = tahun

    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['pengajaran/ajax-local-jadwal']).'\',
        async: true,
        beforeSend : function(){

        },
        success: function(res){
            var res = $.parseJSON(res);
            var row = ""
            $("#tabel-pengajaran > tbody").empty()
            var total_sks = 0
            $.each(res, function(i,obj){
                let isClaimed = obj.is_claimed == 1 ? "checked" : "";
                row += "<tr>"
                row += "<td>"+(i+1)+"</td>"
                row += "<td>"+obj.kode_mk+"</td>"
                row += "<td>"+obj.matkul+"</td>"
                row += "<td>"+obj.sks+"</td>"
                row += "<td>"+obj.kelas+"</td>"
                row += "<td>"+obj.jurusan+"</td>"
                row += "<td>"+obj.tahun_akademik+"</td>"
                row += "<td>"+obj.sks+"</td>"
                row += "<td><input type=\'checkbox\' "+isClaimed+" data-ta=\'"+obj.tahun_akademik+"\' data-sks=\'"+obj.sks+"\' data-item=\'"+obj.ID+"\' class=\'btn-claim\'/></td>"
                row += "</tr>"

                total_sks += eval(obj.sks)
            })

            $("#tabel-pengajaran > tbody").append(row)
                
        }

    });
}

$(document).on("change","#ganti-periode",function(e){
    e.preventDefault()

    getJadwal($(this).val())
    getPublikasi($(this).val())
    getPengabdian($(this).val())
    getPenunjang($(this).val())

  

})

 $("#ganti-periode").trigger("change")


', \yii\web\View::POS_READY);

?>