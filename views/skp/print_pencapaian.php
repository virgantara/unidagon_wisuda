<?php
use app\helpers\MyHelper;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use kartik\depdrop\DepDrop;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use kartik\select2\Select2;
use kartik\editable\Editable;

$list_status_skp = MyHelper::statusSkp();
$list_staf = MyHelper::listRoleStaf();
$nama_pegawai = '';
if(in_array($model->pegawaiDinilai->access_role, $list_staf))
{
    $nama_pegawai = $model->pegawaiDinilai->tendik->nama;
}

else
{
    $nama_pegawai = !empty($model->pegawaiDinilai) && !empty($model->pegawaiDinilai->dataDiri) ? $model->pegawaiDinilai->dataDiri->gelar_depan.' '.$model->pegawaiDinilai->dataDiri->nama.' '.$model->pegawaiDinilai->dataDiri->gelar_belakang : '-';
}
?>
<table width="100%">
  <tr>
    
    <td style="text-align: center;">
      <span style="font-size: 1.15em">PENILAIAN CAPAIAN SASARAN KINERJA PEGAWAI</span><br>
      <span style="font-size: 1.15em">UNIVERSITAS DARUSSALAM GONTOR</span><br>
    </td>
  </tr>
</table>
<br><br>
<table width="100%">
    <tbody>
        <tr>
            <th colspan="3"><h3>I. Pejabat Penilai</h3></th>
            <th colspan="3"><h3>II. Pegawai Dinilai</h3></th>
        </tr>
        <tr>
            <th width="10%">Nama</th>
            <th width="40%">: <?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->gelar_depan.' '.$model->pejabatPenilai->dataDiri->nama.' '.$model->pejabatPenilai->dataDiri->gelar_belakang : '-'?></th>
            <th width="10%">Nama</th>
            <th width="40%">: <?=$nama_pegawai;?></th>
        </tr>
        <tr>
            <th>NIY</th>
            <th>: <?=!empty($model->pejabatPenilai) ? $model->pejabat_penilai : '-'?></th>
            <th>NIY</th>
            <th>: <?=$model->pegawaiDinilai->NIY;?></th>
        </tr>
        <tr>
            <th>Pangkat</th>
            <th>: <?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->namaPangkat : '-'?></th>
            <th>Pangkat</th>
            <th>:<?php 
                    if(!in_array($model->pegawaiDinilai->access_role, $list_staf))
                    {
                    ?>
                <?=$model->pegawaiDinilai->dataDiri->namaPangkat;?>
                <?php } ?></th>
        </tr>
        <tr>
            <th>Jabatan</th>
            <th>: <?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->namaJabfung : '-'?></th>
            <th>Jabatan</th>
            <th>: <?php 
                    if(!in_array($model->pegawaiDinilai->access_role, $list_staf))
                    {
                    ?>
                <?=$model->pegawaiDinilai->dataDiri->namaJabfung;?>
                    <?php } ?></th>
        </tr>
        <tr>
            <th>Unit Kerja</th>
            <th>: <?=!empty($model->jabatanPenilai) && !empty($model->jabatanPenilai->unker) ? $model->jabatanPenilai->unker->nama : '-'?></th>
            <th>Unit Kerja</th>
            <th>: <?=!empty($model->jabatanPegawai) && !empty($model->jabatanPegawai->unker) ? $model->jabatanPegawai->unker->nama : '-'?></th>
        </tr>
        
    </tbody>
</table>
<br><br>
<table width="100%" border="1" cellpadding="2" cellspacing="0">
    <thead>
        <tr>
            <th rowspan="2"  width="5%">No</th>
            <th rowspan="2"  width="24%">Kegiatan</th>
            <th rowspan="2"  width="5%">AK</th>
            <th colspan="4"  width="24%" style="text-align: center;">Target</th>
            <th colspan="4"  width="24%" style="text-align: center;">Realisasi</th>
            <th rowspan="2"  width="9%">Penghitungan</th>
            <th rowspan="2"  width="9%">Capaian SKP</th>
        </tr>
        <tr>
            <th width="6%">Output</th>
            <th width="6%">Mutu</th>
            <th width="6%">Waktu</th>
            <th width="6%">Biaya</th>
            <th width="6%">Output</th>
            <th width="6%">Mutu</th>
            <th width="6%">Waktu</th>
            <th width="6%">Biaya</th>
        </tr>
        
    </thead>
    <tbody>
        
        <?php 
        $capaian_total = 0;
        $counter=0;
        foreach($model->skpItems as $q => $item)
        {
            $counter++;

            $item->hitungSkp();
            $penghitungan = $item->capaian;
            $capaian_skp = $item->capaian_skp;
            $capaian_total += $capaian_skp;


         ?>
        
        <tr>
            <td width="5%"><?=$q+1;?></td>
            <td width="24%"><?=$item->nama;?></td>
            <td width="5%" style="text-align: center;"><?=$item->target_ak;?></td>
            <td width="6%" style="text-align: center;"><?=$item->target_qty;?> <?=$item->target_satuan;?></td>
            <td width="6%" style="text-align: center;"><?=$item->target_mutu;?></td>
            <td width="6%" style="text-align: center;"><?=$item->target_waktu;?> <?=$item->target_waktu_satuan;?></td>
            <td width="6%" style="text-align: center;"><?=MyHelper::formatRupiah($item->target_biaya);?></td>

            <td width="6%" style="text-align: center;"><?=$item->realisasi_qty;?> <?=$item->target_satuan;?></td>
            <td width="6%" style="text-align: center;"><?=$item->realisasi_mutu;?></td>
            <td width="6%" style="text-align: center;"><?=$item->realisasi_waktu;?> <?=$item->target_waktu_satuan;?></td>
            <td width="6%" style="text-align: center;"><?=MyHelper::formatRupiah($item->realisasi_biaya);?></td>
            <td width="9%" style="text-align: center;"><?=$penghitungan?></td>
            <td width="9%" style="text-align: center;"><?=round($capaian_skp,2)?></td>
            
        </tr>
    <?php 

    } 

    $avg_capaian_skp = $capaian_total / $counter;
    $kesimpulan = '';

    if($avg_capaian_skp <= 50)
        $kesimpulan = 'Buruk';
    else if($avg_capaian_skp <= 60)
        $kesimpulan = 'Sedang';
    else if($avg_capaian_skp <= 75)
        $kesimpulan = 'Cukup';
    else if($avg_capaian_skp <= 90.99)
        $kesimpulan = 'Baik';
    else
        $kesimpulan = 'Baik Sekali';
   
    ?>
    <tr>
        <td colspan="13" width="91%" style="text-align:right;">Nilai Capaian SKP</td>
        <td width="9%"><?=round($avg_capaian_skp,2);?>
        (<b><?=$kesimpulan;?></b>)
        </td>
    
    </tr>
    </tbody>
   
</table>


<br><br>
<table border="0" width="100%" cellpadding="1" cellspacing="0">   
    
    <tr>
      <th  style="text-align: center;" width="50%">
        <br>
        <br>
        <br>
        Pejabat Penilai,
        
        <br>
        <br>
        <br>
        <br>
        
        <u><?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->gelar_depan.' '.$model->pejabatPenilai->dataDiri->nama.' '.$model->pejabatPenilai->dataDiri->gelar_belakang : '-'?></u>
        <br>
        NIY: <?=!empty($model->pejabatPenilai) ? $model->pejabat_penilai : '-'?>
      </th>
      <th  style="text-align: center;" width="50%">
        <br>
        <br>
        Ponorogo, <?=date('d-m-Y')?>
        <br>
        Pegawai Dinilai,
        
        <br>
        <br>
        <br>
        <br>
        <u><?=$nama_pegawai;?></u>
        <br>
        NIY: <?=$model->pegawaiDinilai->NIY;?>
      </th>
    </tr>
    
</table>