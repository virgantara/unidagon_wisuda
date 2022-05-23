<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\helpers\MyHelper;
use app\assets\IntroAsset;
IntroAsset::register($this);

$this->title = "Simpulan BKD";

$min_sks = 0;
$max_sks = 0;
$syarat_pendidikan = '';
$syarat_penelitian = '';
$syarat_pengabdian = '';
$syarat_penunjang = '';
$syarat_pendidikan_penelitian = '';
$syarat_pengabdian_penunjang = '';

if(in_array($dataDiri->tugas_dosen_id, ['DT','PT'])){
    $min_sks = 3;
    $max_sks = 16;
    $syarat_pendidikan = 'Minimal 3 sks';
    $syarat_pendidikan_penelitian = 'Minimal 3 sks';
    $syarat_penelitian = 'Boleh kosong';
    $syarat_pengabdian = 'Boleh kosong';
    $syarat_penunjang = 'Boleh kosong';
    $syarat_pengabdian_penunjang = 'Boleh kosong';
}

?>
<h1><?=$this->title;?></h1>

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
                                <td><span style="color:green"><?=$results['pendidikan_selesai']+$results['pendidikan_berlanjut'];?> </span></td>
                                <td><?=$results['pendidikan_lebih'];?></td>
                                <td><span style="color:green">M</span></td>
                                                                        
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Pelaksanaan Penelitian</td>
                                <td><?=$syarat_penelitian;?></td>
                                <td><span style="color:green"><?=$results['penelitian_selesai']+$results['penelitian_berlanjut'];?></span></td>
                                <td><?=$results['penelitian_lebih'];?></td>
                                <td><span style="color:green">M</span></td>
                                                                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Pelaksanaan Pengabdian</td>
                                <td><?=$syarat_pengabdian;?></td>
                                <td><span style="color:green"><?=$results['pengabdian_selesai']+$results['pengabdian_berlanjut'];?></span></td>
                                <td><?=$results['pengabdian_lebih'];?></td>
                                <td><span style="color:green">M</span></td>
                                                                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Pelaksanaan Penunjang</td>
                                <td><?=$syarat_penunjang;?></td>
                                <td><span style="color:green"><?=$results['penunjang_selesai']+$results['penunjang_berlanjut'];?></span></td>
                                <td><?=$results['penunjang_lebih'];?></td>
                                <td><span style="color:green">M</span></td>
                            </tr>
                            <tr>
                                <td class="bg-warning"></td>
                                <td class="bg-warning"><strong><i>Kriteria Pelaksanaan Pendidikan dan Pelaksanaan Penelitian</i></strong></td>
                                <td class="bg-warning"><strong><i><?=$syarat_pendidikan_penelitian;?></i></strong></td>
                                <td class="bg-warning"><strong><i><span style="color:green"><?=$results['total_pendidikan_penelitian'];?></span></i></strong></td>
                                <td class="bg-warning"><strong><i><?=$results['total_pendidikan_penelitian_lebih'];?></i></strong></td>
                                <td class="bg-warning"><strong><i><span style="color:green">M</span></i></strong></td>
                                                                            </tr>
                            <tr>
                                                                    <td class="bg-warning"></td>
                                <td class="bg-warning"><strong><i>Kriteria Pelaksanaan Pengabdian dan Pelaksanaan Penunjang</i></strong></td>
                                <td class="bg-warning"><strong><i><?=$syarat_pengabdian_penunjang;?></i></strong></td>
                                <td class="bg-warning"><strong><i><span style="color:green"><?=$results['total_pengabdian_penunjang'];?></span></i></strong></td>
                                <td class="bg-warning"><strong><i><?=$results['total_pengabdian_penunjang_lebih'];?></i></strong></td>
                                <td class="bg-warning"><strong><i><span style="color:green">M</span></i></strong></td>
                                                                            </tr>
                            <tr>
                            <td class="bg-info" colspan="2"><strong>Total Kinerja</strong></td>
                            <td class="bg-info">Minimal <?=$min_sks;?> sks dan Maksimal <?=$max_sks;?> sks</td>
                            <td class="bg-info"><span style="color:green"><?=$results['total_selesai']+$results['total_berlanjut'];?></span></td>
                            <td class="bg-info"><strong><?=$results['total_lebih'];?></strong></td>
                            <td class="bg-info"><span style="color:green">M</span></td>
                                                       
                             </tr>
                        </tbody>
                    </table>
                <hr>
                <div align="center">
                    <h3 class="text-success">Memenuhi ketentuan perundang-undang beban kerja dosen.</h3>
                </div>
        </div>
    </div>
    </div>
</div>

<?php 

$this->registerJs(' 



', \yii\web\View::POS_READY);

?>