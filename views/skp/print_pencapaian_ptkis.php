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

setlocale(LC_ALL, 'id_ID', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');

$tgl_awal = strftime('%d %B %Y',strtotime($periode->tanggal_skp_awal));
$tgl_akhir = strftime('%d %B %Y',strtotime($periode->tanggal_skp_akhir));
$font_size = '0.65em';
?>
<table width="100%">
  <tr>
    
    <td style="text-align: center;">
      <span>PENILAIAN SASARAN KINERJA</span>
    </td>
  </tr>
</table>
<span style="font-size:0.6em">
Jangka waktu penilaian <?=$tgl_awal?> s/d <?=$tgl_akhir?>
</span>
<br>
<table width="100%" border="1" cellpadding="2" cellspacing="0">
    <thead>
        <tr>
            <th rowspan="2"  width="4%" style="text-align: center;font-size:<?=$font_size;?>"><br><br>No</th>
            <th rowspan="2"  width="25%" style="text-align: center;font-size:<?=$font_size;?>"><br><br>I. Kegiatan Tugas Jabatan</th>
            <th rowspan="2"  width="3%" style="text-align: center;font-size:<?=$font_size;?>"><br><br>AK</th>
            <th colspan="6"  width="24%" style="text-align: center;font-size:<?=$font_size;?>">Target</th>
            <th rowspan="2"  width="3%" style="text-align: center;font-size:<?=$font_size;?>"><br><br>AK</th>
            <th colspan="6"  width="24%" style="text-align: center;font-size:<?=$font_size;?>">Realisasi</th>
            <th rowspan="2"  width="8%" style="text-align: center;font-size:<?=$font_size;?>"><br><br>Penghitungan</th>
            <th rowspan="2"  width="9%" style="text-align: center;font-size:<?=$font_size;?>"><br><br>Capaian SKP</th>
        </tr>
        <tr>
            <th width="6%" colspan="2" style="font-size:0.5em">Kuant/Output</th>
            <th width="6%" style="font-size:0.5em">Kual/Mutu</th>
            <th width="6%" colspan="2" style="font-size:<?=$font_size;?>">Waktu</th>
            <th width="6%" style="font-size:<?=$font_size;?>">Biaya</th>
            <th width="6%" colspan="2" style="font-size:0.5em">Kuant/Output</th>
            <th width="6%" style="font-size:0.5em">Kual/Mutu</th>
            <th width="6%" colspan="2" style="font-size:<?=$font_size;?>">Waktu</th>
            <th width="6%" style="font-size:<?=$font_size;?>">Biaya</th>
        </tr>
        
    </thead>
    <tbody>
        <tr>
            <td width="4%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;">1</td>
            <td width="25%" colspan="2" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;">2</td>
            <td width="3%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;">3</td>
            <td width="3%" style="background-color: lightgray;"></td>
            <td width="3%" style="background-color: lightgray;"></td>
            <td width="6%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;">4</td>
            <td width="3%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;">5</td>
            <td width="3%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;"></td>
            <td width="6%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;">6</td>
            <td width="3%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;"></td>
            <td width="3%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;">7</td>
            <td width="3%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;"></td>
            <td width="6%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;">8</td>
            <td width="3%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;">9</td>
            <td width="3%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;"></td>
            <td width="6%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;">10</td>
            <td width="8%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;">11</td>
            <td width="9%" style="background-color: lightgray;font-size: <?=$font_size;?>;text-align: center;">12</td>
          </tr>
        <?php 
        $capaian_total = 0;
        $counter=0;
        // foreach($model->skpItems as $q => $item)
        // {
        //     $counter++;

        //     $item->hitungSkp();
        //     $penghitungan = $item->capaian;
        //     $capaian_skp = $item->capaian_skp;
        //     $capaian_total += $capaian_skp;


        ?>
        <?php 
          // $counter = 0;
          $total_ak = 0;
        foreach($list_unsur as $q => $v):
            foreach($list_tridharma[$q] as $item):
                $counter++;
                $total_ak += $item->target_ak;
                $penghitungan = $item->capaian;
                $capaian_skp = $item->capaian_skp;
          ?>
          
          <tr>
            <td width="29%" colspan="2" style="font-size: <?=$font_size;?>"><?=$v;?></td>
            <td width="3%"></td>
            <td width="6%" colspan="2"></td>
            <td width="6%"></td>
            <td width="6%" colspan="2"></td>
            <td width="6%"></td>
            <td width="3%"></td>
            <td width="6%"></td>
            <td width="6%"></td>
            <td width="6%"></td>
            <td width="6%"></td>
            <td width="8%"></td>
            <td width="9%"></td>
          </tr>
        <tr>
            <td width="4%" style="font-size:<?=$font_size;?>"><?=$counter;?></td>
            <td width="25%" style="font-size:<?=$font_size;?>"><?=$item->nama;?></td>
            <td width="3%" style="text-align: center;font-size:<?=$font_size;?>"><?=$item->target_ak;?></td>
            <td width="3%" style="text-align: center;font-size:<?=$font_size;?>"><?=$item->target_qty;?> </td>
            <td width="3%" style="text-align: center;font-size:<?=$font_size;?>"><?=$item->target_satuan;?></td>
            <td width="6%" style="text-align: center;font-size:<?=$font_size;?>"><?=$item->target_mutu;?></td>
            <td width="3%" style="text-align: center;font-size:<?=$font_size;?>"><?=$item->target_waktu;?></td>
            <td width="3%" style="text-align: center;font-size:<?=$font_size;?>"><?=$item->target_waktu_satuan;?></td>
            <td width="6%" style="text-align: right;font-size:<?=$font_size;?>"><?=MyHelper::formatRupiah($item->target_biaya);?></td>
            <td width="3%" style="text-align: center;font-size:<?=$font_size;?>"><?=$item->realisasi_ak;?></td>
            <td width="3%" style="text-align: center;font-size:<?=$font_size;?>"><?=$item->realisasi_qty;?></td>
            <td width="3%" style="text-align: center;font-size:<?=$font_size;?>"><?=$item->target_satuan;?></td>
            <td width="6%" style="text-align: center;font-size:<?=$font_size;?>"><?=$item->realisasi_mutu;?></td>
            <td width="3%" style="text-align: center;font-size:<?=$font_size;?>"><?=$item->realisasi_waktu;?> </td>
            <td width="3%" style="text-align: center;font-size:<?=$font_size;?>"><?=$item->target_waktu_satuan;?></td>
            <td width="6%" style="text-align: right;font-size:<?=$font_size;?>"><?=MyHelper::formatRupiah($item->realisasi_biaya);?></td>
            <td width="8%" style="text-align: center;font-size:<?=$font_size;?>"><?=round($penghitungan,2)?></td>
            <td width="9%" style="text-align: center;font-size:<?=$font_size;?>"><?=round($capaian_skp,2)?></td>
            
        </tr>
    <?php 
            endforeach;
        endforeach;

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
        <td rowspan="2" colspan="13" width="91%" style="text-align:center; "><br>Nilai Capaian SKP</td>
        <td width="9%" style="font-size:<?=$font_size;?>;text-align: center;"><?=round($avg_capaian_skp,2);?>
        
        </td>
    
    </tr>
    <tr>
        <td width="9%" style="font-size:<?=$font_size;?>;text-align: center;">
        (<b><?=$kesimpulan;?></b>)
        </td>
    
    </tr>
    </tbody>
   
</table>


<br><br>
<table width="100%">
  <tr>
    <td width="50%" style="text-align:center;font-size:<?=$font_size;?>;">
      <br><br><br><br><br><br><br><br><br><br>
    </td>
    <td width="50%" style="text-align:center;font-size:0.85em;">
      Ponorogo, <?=$tgl_akhir;?><br>
      Pejabat Penilai
    </td>
  </tr>
  <tr>
    <td style="text-align:center;"></td>
    <td  style="text-align:center;font-size:0.85em"><?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->gelar_depan.' '.$model->pejabatPenilai->dataDiri->nama.' '.$model->pejabatPenilai->dataDiri->gelar_belakang : '-'?>
      <br>
      <?=!empty($model->pejabatPenilai) ? $model->pejabat_penilai : '-'?>

      <br><br>
        Catatan:<br><br>
      * AK bagi dosen yang memangku jabatan tertentu
    </td>
  </tr>
</table>