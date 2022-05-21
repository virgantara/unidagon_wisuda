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
$this->title = 'Pelaksanaan Pendidikan';

$list_status = \app\helpers\MyHelper::getListStatusBKD();
$list_status_color = \app\helpers\MyHelper::getListStatusBKDColor();
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
    <li role="presentation" class="active">
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
    <li role="presentation" class="">
      <a href="<?=Url::to(['bkd/klaim','step'=>5]);?>"  >Simpulan</a>
    </li>
</ul>
<div class="row">
	<div class="col-md-12">
		<div class="panel">
			<div class="panel-heading">
				<div class="pull-left">A. Melaksanakan perkuliahan (tutorial, tatap muka, dan/atau daring) dan membimbing, menguji serta menyelenggarakan pendidikan di laboratorium, praktik keguruan bengkel/ studio/ kebun (tatap muka dan/atau daring) pada institusi pendidikan sesuai penugasan</div>
                <div class="pull-right"><a href="javascript:void(0)" id='btn_tarik_pengajaran' class="btn btn-primary"><i class="fa fa-refresh"></i> Tarik</a></div>
			</div>
			<div class="panel-body">
				<table class="table table-striped table-hover table-bordered" id="tabel-pengajaran">
					<thead>
						<tr>
							<th>No</th>
							<th>Kegiatan</th>
							<th>Rencana Pertemuan</th>
							<th class="text-center">sks MK Terhitung</th>
							<th class="text-center">sks BKD</th>
							<th>Status</th>
                            <th>Opsi</th>
						</tr>
					</thead>
					<tbody>
						<?php 

                        $total_sks_mk = 0;
                        $total_sks_bkd = 0;
                        foreach($results_pengajaran as $q => $item): 

                            $color = $list_status_color[$item['status_bkd']];

                            $total_sks_mk += (float) $item['sks_mk'];
                            $total_sks_bkd += (float) $item['sks'];
                        ?>
                        <tr>
                            <td><?=$q+1;?></td>
                            <td><?=$item['deskripsi'];?></td>
                            <td><?=$item['rencana'];?></td>
                            <td class="text-center"><?=$item['sks_mk'];?> sks</td>
                            <td class="text-center"><?=$item['sks'];?></td>
                            <td>

                                <div class="btn-group">
                                  <button type="button" class="btn btn-<?=$color;?> btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <?=(!empty($list_status[$item['status_bkd']]) ? $list_status[$item['status_bkd']] : null);?> <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu" role="menu">
                                    <?php foreach($list_status as $q=>$status): ?>
                                    <li><a href="#" data-item="<?=$q;?>" class="btn_ubah_status_bkd" data-key="<?=$item['id'];?>"><?=$status;?></a></li>
                                    <?php endforeach ?>
                                  </ul>
                                </div>
                            </td>
                            <td><a href='javascript:void(0)' data-item='"+obj.id+"' class='remove_bkd'><i class='fa fa-trash'></i></a></td>
                        </tr>
                        <?php 
                        endforeach; 
                        ?>
					</tbody>
					<tfoot>
                        <tr>
                            <td colspan="3" class="text-center alert alert-info" >Total SKS</td>
                            <td class="text-center alert alert-info"><?=$total_sks_mk;?> sks</td>
                            <td class="text-center alert alert-info"><?=$total_sks_bkd;?></td>
                            <td colspan="2" class=" alert alert-info"></td>
                        </tr>
                    </tfoot>
				</table>
			</div>
		</div>
	</div>
</div>

<?php 
foreach($list_komponen_utama as $i => $komponen_utama):
    if($i == 0) continue;
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="pull-left"><?=$komponen_utama['nama'];?></div>
                <div class="pull-right"><a href="javascript:void(0)" data-item="<?=$komponen_utama['nama'];?>" class="btn btn-primary btn_tarik_pendidikan"><i class="fa fa-refresh"></i> Tarik</a></div>
            </div>
            <div class="panel-body">
                <table class="table" id="tabel-pendidikan-<?=$komponen_utama['nama'];?>">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="30%">Nama Kegiatan</th>
                            <th width="10%">Status</th>
                            <th width="35%">Jumlah Kegiatan</th>
                            <th width="10%">Beban Tugas</th>
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
                            <td><?=$item['deskripsi'];?></td>
                            
                            <td>

                                <div class="btn-group">
                                  <button type="button" class="btn btn-<?=$color;?> btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <?=(!empty($list_status[$item['status_bkd']]) ? $list_status[$item['status_bkd']] : null);?> <span class="caret"></span>
                                  </button>
                                  <ul class="dropdown-menu" role="menu">
                                    <?php foreach($list_status as $q=>$status): ?>
                                    <li><a href="#" data-item="<?=$q;?>" class="btn_ubah_status_bkd" data-key="<?=$item['id'];?>"><?=$status;?></a></li>
                                    <?php endforeach ?>
                                  </ul>
                                </div>
                            </td>
                            <td></td>
                            <td><?=$item['sks'];?></td>
                            <td><a href='javascript:void(0)' data-item='"+obj.id+"' class='remove_bkd'><i class='fa fa-trash'></i></a></td>
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

$(document).on("click","#btn_tarik_pengajaran",function(e){
    e.preventDefault()

    var obj = new Object
    
    if(!$("#ganti-periode").val()){
        Swal.fire({
          title: \'Oops!\',
          icon: \'error\',
          text: "Silakan Pilih Periode BKD" 
        })

        return
    }
    obj.tahun_id = "'.(!empty($tahun_id) ? $tahun_id : "").'"
    
    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['skp-item/ajax-claim-pengajaran']).'\',
        async: true,
        beforeSend : function(){
            Swal.showLoading()
        },
        error : function(e){
            Swal.fire({
              title: \'Oops!\',
              icon: \'error\',
              text: e.responseText 
            })
        },
        success: function(res){
            Swal.close()
            var res = $.parseJSON(res);
            if(res.code == 200)
                window.location.reload()
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


$(document).on("click",".btn_tarik_pendidikan",function(e){
    e.preventDefault()

    var obj = new Object
    obj.nama_komponen_kegiatan = $(this).data("item")
    obj.tahun_id = $("#ganti-periode").val()
    
    $.ajax({
        type : \'POST\',
        data : {
            dataPost : obj
        },
        url : \''.Url::to(['skp-item/ajax-claim-pendidikan']).'\',
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
                    if(res.code == 200)
                        window.location.reload()
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
          confirmButtonColor: \'#d33\',
          cancelButtonColor: \'#3085d6\',
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

', \yii\web\View::POS_READY);

?>