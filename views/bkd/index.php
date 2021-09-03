<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$list_bkd_periode = \app\models\BkdPeriode::find()->orderBy(['tahun_id'=>SORT_DESC])->all();
$list_tahun = ArrayHelper::map($list_bkd_periode,'tahun_id','nama_periode');

/* @var $this yii\web\View */

$this->title = 'UNIDA Gontor Lecturer Data';
$total_abdi = 0;
$total_penunjang = 0;
$total_ajar = 0;
$total_pub = 0;
$total_ajar = 0; 
foreach ($pengajaran as $key => $value) 
{
    if($value->is_claimed == '1')
        $total_ajar += $value->sks_bkd * $value->sks;
}

foreach ($publikasi as $key => $value) 
{
    $total_pub += $value->sks_bkd;
}

foreach ($pengabdian as $key => $value) 
{
    $total_abdi += $value->nilai;
}

foreach ($organisasi as $key => $value) 
{
    $total_penunjang += $value->sks_bkd;
}
foreach ($pengelolaJurnal as $key => $value) 
{
    $total_penunjang += $value->sks_bkd;
}

$total_bkd = $total_ajar+$total_pub+$total_abdi+$total_penunjang;

$persen_a = 0;
$persen_b = 0;
$persen_c = 0;
$persen_d = 0;
$label_a = '';
$label_b = '';
$label_c = '';
$label_d = '';
$num_bkd_ajar = $bkd_ajar->nilai_minimal > 0 ? $bkd_ajar->nilai_minimal : 1;
$num_bkd_pub = $bkd_pub->nilai_minimal;
$num_bkd_abdi = $bkd_abdi->nilai_minimal;
$num_bkd_penunjang = $bkd_penunjang->nilai_minimal;
$persen_a = round(($total_ajar) / ($num_bkd_ajar) * 100,2);
$persen_b = !empty($num_bkd_pub) ? round(($total_pub) / ($num_bkd_pub) * 100,2) : 0;
$persen_c = !empty($num_bkd_abdi) ? round(($total_abdi) / ($num_bkd_abdi) * 100,2) : 0;
$persen_d = !empty($num_bkd_penunjang) ? round(($total_penunjang) / ($num_bkd_penunjang) * 100,2) : 0;

$is_cukup_ab = false;
$label_ab = '';
$is_cukup_cd = false;
$label_cd = '';
$status_dosen = $user->dataDiri->tugasDosen->id;

if($status_dosen == 'DT')
{
    $is_cukup_ab = $total_ajar > $num_bkd_ajar;
   
    $is_cukup_cd = true;
}

else if($status_dosen == 'DS')
{
    $is_cukup_ab = ($total_ajar > $num_bkd_ajar && $total_pub > $num_bkd_pub);

    if((!empty($total_abdi) && !empty($total_penunjang)) && ($total_abdi > $num_bkd_abdi && $total_penunjang > $num_bkd_penunjang))
    {
        $is_cukup_cd = $total_abdi + $total_penunjang >= 3;
    }
}

else if($status_dosen == 'PS')
{
    $is_cukup_ab = ($total_ajar > $num_bkd_ajar && $total_pub > $num_bkd_pub);
    if($total_abdi > $num_bkd_abdi && $total_penunjang > $num_bkd_penunjang)
    {
        $is_cukup_cd = $total_abdi + $total_penunjang >= 3;
    }
}

else if($status_dosen == 'PT')
{
    $is_cukup_ab = $total_ajar > $num_bkd_ajar;
    $is_cukup_cd = true;
}

if($is_cukup_ab){
    $label_ab = '<span style="color:#5cb85c"><i class="lnr lnr-thumbs-up"></i> sudah mencukupi</label>';
}

else{
    $label_ab = '<span style="color:#d9534f"><i class="lnr lnr-thumbs-down"></i> belum mencukupi</label>';
}

if($is_cukup_cd){
    $label_cd = '<span style="color:#5cb85c"><i class="lnr lnr-thumbs-up"></i> sudah mencukupi</label>';
}

else{
    $label_cd = '<span style="color:#d9534f"><i class="lnr lnr-thumbs-down"></i> belum mencukupi</label>';
}

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
<h1>Progres BKD Anda Semester ini (<?=$bkd_periode;?>)</h1>
<h3>Tanggal <?=\app\helpers\MyHelper::convertTanggalIndo($sd);?> sampai dengan <?=\app\helpers\MyHelper::convertTanggalIndo($ed);?></h3>
<p>
<?php
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'action' => ['bkd/ganti-periode'],
]); ?>
    <?= Html::dropDownList('tahun','', $list_tahun, ['id' => 'ganti-periode','prompt'=>'- Pilih Periode -']) ?>
    <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
 <?php ActiveForm::end(); ?>
 <?php 
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
      echo '<div class="alert alert-' . $key . '">' . $message . '<button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button></div>';
    }
    ?>
</p>
<div class="row">
    <div class="col-md-12">	
        <div class="panel">
            <div class="panel-heading">
            	<?=Html::a('<i class="fa fa-print"></i> Cetak LKD',['bkd/print'],['class'=>'btn btn-success']);?>
            </div>
            <div class="panel-body">
            	<div class="table-responsive">
                    
            		<table class="table table-striped table-hover">
            			<thead>
            				<tr>
            					<th>No</th>
                                <th>Unsur Utama</th>
            					<th class="text-center">Kegiatan</th>
            					<th class="text-center">Beban Kredit</th>
            					
            			    </tr>
            			</thead>
            			<tbody>
                            <?php 
                            $counter = 0;
                            $total =0;
                            foreach ($results as $key => $value) 
                            {
                                if(empty($value['items'])) continue;
                            
                				$subtotal = 0; 
                				foreach ($value['items'] as $q => $v) 
                				{
                                    $counter++;

            					# code...
            					   $subtotal += $v->sks;
            				?>
            				<tr>
            					<td><?=$counter ;?></td>
                                <td><?=$value['unsur'];?></td>
            					<td><?=$v->deskripsi;?></td>
            					<td class="text-center"><?=$v->sks;?></td>
            				</tr>
                				<?php 
                				}

                                $total += $subtotal;
                				?>
            			</tbody>
            			<?php 
                        }
                        ?>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right">Total Kredit</td>
                                <td class="text-center"><?=$total;?></td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>Kesimpulan</strong></td>
                                <td colspan="2">
                                    <p>
                                        Kegiatan Pengajaran dan Penelitian Anda <?=$label_ab;?>
                                    </p>
                                    <p>
                                        Kegiatan Pengabdian dan Penunjang Anda <?=$label_cd;?>
                                    </p>
                                </td>
                            </tr>

                        </tfoot>
            		</table>
            	</div>
            	
            </div>
        </div>
    </div>
</div>   


<?php 

$this->registerJs(' 
    

', \yii\web\View::POS_READY);

?>