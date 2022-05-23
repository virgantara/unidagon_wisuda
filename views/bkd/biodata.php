<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\helpers\MyHelper;

$this->title = 'Biodata';
$this->params['breadcrumbs'][] = $this->title;
$list_status = \app\helpers\MyHelper::getListStatusBKD();
$list_status_color = \app\helpers\MyHelper::getListStatusBKDColor();

$list_status_aktif = \app\helpers\MyHelper::getStatusAktif();
?>
<h1><?=$this->title;?></h1>

<ul class="nav nav-tabs">
    <li role="presentation" class="active">
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
    <li role="presentation" class="">
      <a href="<?=Url::to(['bkd/klaim','step'=>6]);?>"  >Simpulan</a>
    </li>
</ul>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                
            </div>
            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <tbody>
                    <tr><td><strong>Nama</strong></td><td>:</td><td><?=strtoupper($dataDiri->nama);?></td></tr>
                    <tr><td><strong>NIP</strong></td><td>:</td><td>-</td></tr>
                    <tr><td><strong>NIDN</strong></td><td>:</td><td><?=$dataDiri->NIDN;?></td></tr>
                    <tr><td><strong>Status Dosen</strong></td><td>:</td><td><?=\app\helpers\MyHelper::getListTugasDosen()[$dataDiri->tugas_dosen_id]?></td></tr>
                    <tr><td><strong>Jabatan Fungsional</strong></td><td>:</td><td><?=$dataDiri->jabatanFungsional->nama;?></td></tr>
                    <tr><td><strong>Jabatan</strong></td><td>:</td><td>-</td></tr>
                    <tr><td><strong>Status Serdos</strong></td><td>:</td><td><?=strlen($dataDiri->no_sertifikat_pendidik) > 10 ? 'Sertifikasi' : 'Belum Sertifikasi'?></td></tr>
                    <tr><td><strong>Nomor Sertifikasi</strong></td><td>:</td><td><?=$dataDiri->no_sertifikat_pendidik?></td></tr>
                    <tr><td><strong>Status Keaktifan</strong></td><td>:</td><td><?=!empty($list_status_aktif[$dataDiri->nIY->status]) ? $list_status_aktif[$dataDiri->nIY->status] : '-';?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php 

$this->registerJs(' 



', \yii\web\View::POS_READY);

?>