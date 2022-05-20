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

?>
<h1>Klaim Kegiatan BKD Periode <?=$bkd_periode;?> (<?=MyHelper::convertTanggalIndo($sd);?> - <?=MyHelper::convertTanggalIndo($ed);?>)</h1>
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
    <li role="presentation" class="active">
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
				<div class="pull-left">Penunjang</div>
                <div class="pull-right"><a href="javascript:void(0)" id='btn_tarik_penunjang' class="btn btn-primary"><i class="fa fa-refresh"></i> Refresh</a></div>
			</div>
			<div class="panel-body">
				<table class="table" id="tabel-penunjang">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kegiatan</th>
                            <th>SKS BKD</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                    
                </table>
			</div>
		</div>
	</div>
</div>



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

$(document).on("change","#ganti-periode",function(e){
    e.preventDefault()

  
    getPenunjang($(this).val())

  

})

 $("#ganti-periode").trigger("change")


', \yii\web\View::POS_READY);

?>