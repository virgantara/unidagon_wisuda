<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\helpers\MyHelper;
use app\assets\IntroAsset;
IntroAsset::register($this);



$this->title = "Simpulan BKD";
$this->params['breadcrumbs'][] = $this->title;
$min_sks = 0;
$max_sks = 16;
$syarat_pendidikan = 'Tidak boleh kosong';
$syarat_penelitian = 'Tidak boleh kosong';
$syarat_pengabdian = 'Tidak boleh kosong';
$syarat_penunjang = 'Tidak boleh kosong';
$syarat_pendidikan_penelitian = 'Minimal 9 sks';
$syarat_pengabdian_penunjang = 'Minimal 3 sks';

$min_sks_pendidikan_penelitian = 9;
$min_sks_pengabdian_penunjang = 3;
$min_sks_pendidikan = 0.01;
$min_sks_pengabdian = 0.01;
$min_sks_penunjang = 0.01;
$min_sks_penelitian = 0.01;

$is_memenuhi_pendidikan = true;
$is_memenuhi_penelitian = true;
$is_memenuhi_pengabdian = true;
$is_memenuhi_penunjang = true;
if(in_array($dataDiri->tugas_dosen_id, ['DT','PT'])){
    $min_sks_pengabdian_penunjang = 0;
    $min_sks_pendidikan_penelitian = 3;
    $min_sks = 3;
    $min_sks_pendidikan = 0;
    $min_sks_pengabdian = 0;
    $min_sks_penunjang = 0;
    $min_sks_penelitian = 0;
    $syarat_pendidikan = 'Minimal 3 sks';
    $syarat_pendidikan_penelitian = 'Minimal 3 sks';
    $syarat_penelitian = 'Boleh kosong';
    $syarat_pengabdian = 'Boleh kosong';
    $syarat_penunjang = 'Boleh kosong';
    $syarat_pengabdian_penunjang = 'Boleh kosong';
}
else{
    $is_memenuhi_pendidikan = $results['pendidikan_selesai'] + $results['pendidikan_berlanjut'] > $min_sks_pendidikan;
    $is_memenuhi_penelitian = $results['penelitian_selesai'] + $results['penelitian_berlanjut'] > $min_sks_penelitian;
    $is_memenuhi_pengabdian = $results['pengabdian_selesai'] + $results['pengabdian_berlanjut'] > $min_sks_pengabdian;
    $is_memenuhi_penunjang = $results['penunjang_selesai'] + $results['penunjang_berlanjut'] > $min_sks_penunjang;
}

$is_memenuhi_semua = $is_memenuhi_pendidikan && $is_memenuhi_penelitian && $is_memenuhi_pengabdian && $is_memenuhi_penunjang;
// echo '<pre>';
// print_r($results);
// exit;

if($results['total_selesai'] + $results['total_berlanjut'] > $max_sks)
    $is_memenuhi_semua = false;

$is_memenuhi_pendidikan_penelitian = $results['total_pendidikan_penelitian'] >= $min_sks_pendidikan_penelitian;
$is_memenuhi_pengabdian_penunjang = $results['total_pengabdian_penunjang'] >= $min_sks_pengabdian_penunjang;

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
    <li role="presentation" class="">
      <a href="<?=Url::to(['bkd/klaim','step'=>5]);?>"  >Pelaksanaan Penunjang</a>
    </li>
    <li role="presentation" class="active">
      <a href="<?=Url::to(['bkd/klaim','step'=>6]);?>"  >Simpulan</a>
    </li>
</ul>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">Kewajiban BKD</div>
            <div class="panel-body">
                <div class="alert alert-info">
                    <strong>Keterangan:</strong>
                    <ul>
                        <li><strong>TM</strong>: Tidak Memenuhi</li>
                        <li><strong>M</strong>: Memenuhi</li>
                    </ul>
                </div>
                <table class="table table-striped table-hover" style="border: 1px solid #e7eaec">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Kinerja</th>
                            <th>Syarat</th>
                            <th>sks BKD</th>
                            <th>sks Lebih</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Pelaksanaan Pendidikan</td>
                            <td><?=$syarat_pendidikan;?></td>
                            <td><?=$results['pendidikan_selesai']+$results['pendidikan_berlanjut'];?></td>
                            <td><?=$results['pendidikan_lebih'];?></td>
                            <td><?=$is_memenuhi_pendidikan ? '<span style="color:green">M</span>' : '<span style="color:red">TM</span>';?>
                                
                            </td>
                                                                    
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Pelaksanaan Penelitian</td>
                            <td><?=$syarat_penelitian;?></td>
                            <td><span style="color:green"><?=$results['penelitian_selesai']+$results['penelitian_berlanjut'];?></span></td>
                            <td><?=$results['penelitian_lebih'];?></td>
                            <td><?=$is_memenuhi_penelitian ? '<span style="color:green">M</span>' : '<span style="color:red">TM</span>';?>
                                
                            </td>
                                                                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Pelaksanaan Pengabdian</td>
                            <td><?=$syarat_pengabdian;?></td>
                            <td><?=$results['pengabdian_selesai']+$results['pengabdian_berlanjut'];?></td>
                            <td><?=$results['pengabdian_lebih'];?></td>
                            <td><?=$is_memenuhi_pengabdian ? '<span style="color:green">M</span>' : '<span style="color:red">TM</span>';?></td>
                                                                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Pelaksanaan Penunjang</td>
                            <td><?=$syarat_penunjang;?></td>
                            <td><?=$results['penunjang_selesai']+$results['penunjang_berlanjut'];?></td>
                            <td><?=$results['penunjang_lebih'];?></td>
                            <td><?=$is_memenuhi_penunjang ? '<span style="color:green">M</span>' : '<span style="color:red">TM</span>';?></td>
                        </tr>
                        <tr>
                            <td class="bg-warning"></td>
                            <td class="bg-warning"><strong><i>Kriteria Pelaksanaan Pendidikan dan Pelaksanaan Penelitian</i></strong></td>
                            <td class="bg-warning"><strong><i><?=$syarat_pendidikan_penelitian;?></i></strong></td>
                            <td class="bg-warning"><strong><i><span style="color:green"><?=$results['total_pendidikan_penelitian'];?></span></i></strong></td>
                            <td class="bg-warning"><strong><i><?=$results['total_pendidikan_penelitian_lebih'];?></i></strong></td>
                            <td class="bg-warning"><strong><i><?=$is_memenuhi_pendidikan_penelitian ? '<span style="color:green">M</span>' : '<span style="color:red">TM</span>';?></i></strong></td>
                                                                        </tr>
                        <tr>
                                                                <td class="bg-warning"></td>
                            <td class="bg-warning"><strong><i>Kriteria Pelaksanaan Pengabdian dan Pelaksanaan Penunjang</i></strong></td>
                            <td class="bg-warning"><strong><i><?=$syarat_pengabdian_penunjang;?></i></strong></td>
                            <td class="bg-warning"><strong><i><span style="color:green"><?=$results['total_pengabdian_penunjang'];?></span></i></strong></td>
                            <td class="bg-warning"><strong><i><?=$results['total_pengabdian_penunjang_lebih'];?></i></strong></td>
                            <td class="bg-warning"><strong><i><?=$is_memenuhi_pengabdian_penunjang ? '<span style="color:green">M</span>' : '<span style="color:red">TM</span>';?></i></strong></td>
                                                                        </tr>
                        <tr>
                        <td class="bg-info" colspan="2"><strong>Total Kinerja</strong></td>
                        <td class="bg-info">Minimal <?=$min_sks;?> sks dan Maksimal <?=$max_sks;?> sks</td>
                        <td class="bg-info">
                        <?php
                            $color = $is_memenuhi_semua ? 'green' : 'red';
                            echo '<span style="color:'.$color.'">'.($results['total_selesai']+$results['total_berlanjut']).'</span>';

                            $label = $is_memenuhi_semua ? 'M' : 'TM';

                        ?>
                        </span></td>
                        <td class="bg-info"><strong><?=$results['total_lebih'];?></strong></td>
                        <td class="bg-info"><?='<span style="color:'.$color.'">'.$label.'</span>'?></td>
                                                   
                         </tr>
                    </tbody>
                </table>
                <hr>
                <div align="center">
                    <?php 
                    if($is_memenuhi_semua){
                    ?>
                    <h3 class="text-success">Memenuhi ketentuan perundang-undang beban kerja dosen.</h3>
                    <?php
                    }
                    else {
                        ?>
                    <h3 class="text-danger">Belum memenuhi ketentuan perundang-undang beban kerja dosen.</h3>
                        <?php  
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 

$this->registerJs(' 



', \yii\web\View::POS_READY);

?>