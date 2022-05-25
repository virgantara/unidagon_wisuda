<?php
use app\helpers\MyHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


setlocale(LC_ALL, 'id_ID', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');

$tgl_awal = strftime('%d %B %Y',strtotime($periode->tanggal_skp_awal));
$tgl_akhir = strftime('%d %B %Y',strtotime($periode->tanggal_skp_akhir));

$list_status_skp = MyHelper::statusSkp();
/* @var $this yii\web\View */
/* @var $model app\models\Skp */

// $list_unsur = ArrayHelper::map(\app\models\UnsurUtama::find()->orderBy(['urutan'=>SORT_ASC])->all(),'id','nama');

// $this->title = 'Form SKP Periode '.date('d-m-Y',strtotime($model->periode->tanggal_bkd_awal)).' s/d '.date('d-m-Y',strtotime($model->periode->tanggal_bkd_akhir));
// $this->params['breadcrumbs'][] = ['label' => 'Skps', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
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


$font_size = '0.85em';
?>
<table width="100%" cellpadding="2">
  <tr>
    <td width="5%" style="text-align: center;border: 1px solid black;font-size:<?=$font_size;?>">NO</td>
    <td width="45%" colspan="2" style="border: 1px solid black;font-size:<?=$font_size;?>">I. PEJABAT PENILAI</td>
    <td width="5%" style="text-align: center;border: 1px solid black;font-size:<?=$font_size;?>">NO</td>
    <td width="45%" colspan="3" style="border: 1px solid black;font-size:<?=$font_size;?>">II. DOSEN PTKIS YANG DINILAI</td>
  </tr>
  <tr>
    <td style="text-align: center;border-left: 1px solid black;font-size:<?=$font_size;?>">1</td>
    <td width="15%" style="border-left: 1px solid black;font-size:<?=$font_size;?>">Nama</td>
    <td width="30%" style="border-left: 1px solid black;font-size:<?=$font_size;?>">
      <?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->gelar_depan.' '.$model->pejabatPenilai->dataDiri->nama.' '.$model->pejabatPenilai->dataDiri->gelar_belakang : '-'?></td>
    <td style="text-align: center;border-left: 1px solid black;font-size:<?=$font_size;?>">1</td>
    <td width="15%" colspan="2" style="border-left: 1px solid black;font-size:<?=$font_size;?>">Nama</td>
    <td width="30%" style="border-right: 1px solid black;border-left: 1px solid black;font-size:<?=$font_size;?>" colspan="3">
      <?=$nama_pegawai?></td>
  </tr>
  <tr>
    <td style="text-align: center;border-left: 1px solid black;font-size:<?=$font_size;?>">2</td>
    <td style="border-left: 1px solid black;font-size:<?=$font_size;?>">NIY</td>
    <td style="border-left: 1px solid black;font-size:<?=$font_size;?>">
      <?=!empty($model->pejabatPenilai) ? $model->pejabat_penilai : '-'?>
    </td>
    <td style="text-align: center;border-left: 1px solid black;font-size:<?=$font_size;?>">2</td>
    <td colspan="2" style="border-left: 1px solid black;font-size:<?=$font_size;?>">NIY</td>
    <td style="border-right: 1px solid black;border-left: 1px solid black;font-size:<?=$font_size;?>" colspan="3">
      <?=$model->pegawaiDinilai->NIY;?>
    </td>
  </tr>
  <tr>
    <td style="text-align: center;border-left: 1px solid black;font-size:<?=$font_size;?>">3</td>
    <td style="border-left: 1px solid black;font-size:<?=$font_size;?>">Pangkat/Gol.Ruang</td>
    <td style="border-left: 1px solid black;font-size:<?=$font_size;?>">
      <?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->namaPangkat : '-'?>
    </td>
    <td style="text-align: center;border-left: 1px solid black;font-size:<?=$font_size;?>">3</td>
    <td colspan="2" style="border-left: 1px solid black;font-size:<?=$font_size;?>">Pangkat/Gol.Ruang</td>
    <td style="border-right: 1px solid black;border-left: 1px solid black;font-size:<?=$font_size;?>" colspan="3">
      <?=$model->pegawaiDinilai->dataDiri->namaPangkat;?>
    </td>
  </tr>
  <tr>
    <td style="text-align: center;border-left: 1px solid black;font-size:<?=$font_size;?>">4</td>
    <td style="border-left: 1px solid black;font-size:<?=$font_size;?>">Jabatan</td>
    <td style="border-left: 1px solid black;font-size:<?=$font_size;?>">
      <?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->namaJabfung : '-'?>
    </td>
    <td style="text-align: center;border-left: 1px solid black;font-size:<?=$font_size;?>">4</td>
    <td colspan="2" style="border-left: 1px solid black;font-size:<?=$font_size;?>">Jabatan</td>
    <td style="border-right: 1px solid black;border-left: 1px solid black;font-size:<?=$font_size;?>" colspan="3">
      <?=$model->pegawaiDinilai->dataDiri->namaJabfung;?>
    </td>
  </tr>
  <tr>
    <td style="text-align: center;border-left: 1px solid black;font-size:<?=$font_size;?>">5</td>
    <td style="border-left: 1px solid black;font-size:<?=$font_size;?>">Unit Kerja</td>
    <td style="border-left: 1px solid black;font-size:<?=$font_size;?>">
      <?=!empty($model->jabatanPenilai) && !empty($model->jabatanPenilai->unker) ? $model->jabatanPenilai->unker->nama : '-'?>
    </td>
    <td style="text-align: center;border-left: 1px solid black;font-size:<?=$font_size;?>">5</td>
    <td colspan="2" style="border-left: 1px solid black;font-size:<?=$font_size;?>">Unit Kerja</td>
    <td style="border-right: 1px solid black;border-left: 1px solid black;font-size:<?=$font_size;?>" colspan="3">
      <?=!empty($model->jabatanPegawai) && !empty($model->jabatanPegawai->unker) ? $model->jabatanPegawai->unker->nama : '-'?>
    </td>
  </tr>
  <tr>
    <td style="border: 1px solid black;font-size:<?=$font_size;?>;text-align: center;" rowspan="2"><br><br>NO</td>
    <td style="border: 1px solid black;font-size:<?=$font_size;?>;text-align: center;height: 34px;" rowspan="2" colspan="2"><br><br>III. KEGIATAN TUGAS JABATAN</td>
    <td style="border: 1px solid black;font-size:<?=$font_size;?>;text-align: center;" rowspan="2"><br><br>AK</td>
    <td style="border: 1px solid black;font-size:<?=$font_size;?>;text-align:center;" colspan="5">TARGET</td>
  </tr>
  <tr>
    <td style="border: 1px solid black;font-size:<?=$font_size;?>;text-align: center;" colspan="2">KUANT/OUTPUT</td>
    <td style="border: 1px solid black;font-size:<?=$font_size;?>;text-align: center;" >KUAL/MUTU</td>
    <td style="border: 1px solid black;font-size:<?=$font_size;?>;text-align: center;" >WAKTU</td>
    <td style="border: 1px solid black;font-size:<?=$font_size;?>;text-align: center;" >BIAYA</td>
  </tr>

  <!-- LOOP ITEMS -->
  <?php 
  $counter = 0;
  $total_ak = 0;
  foreach($list_unsur as $q => $v):
  ?>
  <tr>
    <td colspan="9" style="border:1px solid black;font-size: <?=$font_size;?>"><?=$v;?></td>
  </tr>
  <?php 
  
  foreach($list_tridharma[$q] as $pd):
    $counter++;
    $total_ak += $pd->target_ak;
  ?>
  <tr>
    <td style="border:1px solid black;text-align: center;font-size:<?=$font_size;?>;"><?=$counter?></td>
    <td colspan="2" style="border:1px solid black;font-size:<?=$font_size;?>;"><?=$pd->nama?></td>
    <td style="border:1px solid black;text-align: center;font-size:<?=$font_size;?>;"><?=$pd->target_ak?></td>
    <td style="border:1px solid black;text-align: center;font-size:<?=$font_size;?>;"><?=$pd->target_qty?></td>
    <td style="border:1px solid black;text-align: center;font-size:<?=$font_size;?>;"><?=$pd->target_satuan?></td>
    <td style="border:1px solid black;text-align: center;font-size:<?=$font_size;?>;"><?=$pd->target_mutu?></td>
    <td style="border:1px solid black;text-align: center;font-size:<?=$font_size;?>;"><?=$pd->target_waktu?> <?=$pd->target_waktu_satuan?></td>
    <td style="border:1px solid black;text-align: center;font-size:<?=$font_size;?>;"><?=MyHelper::formatRupiah($pd->target_biaya)?></td>
  </tr>
  <?php endforeach ?>
  <?php endforeach ?>
  
  <!-- END LOOP ITEMS -->
  <tr>
    <td style="border:1px solid black;text-align: center;" colspan="3">JUMLAH</td>
    <td style="border:1px solid black;text-align: center;font-size:<?=$font_size;?>;"><?=$total_ak?></td>
    <td style="border:1px solid black" colspan="5"></td>
  </tr>
</table>
<br><br>
<table width="100%">
  <tr>
    <td width="50%" style="text-align:center;">
      Pejabat Penilai<br><br><br><br><br><br>
    </td>
    <td width="50%" style="text-align:center;">
      Ponorogo, <?=$tgl_akhir;?><br>
      Dosen PTKIS Yang Dinilai
    </td>
  </tr>
  <tr>
    <td style="text-align:center;"><?=$nama_pegawai;?><br><?=$model->pegawaiDinilai->NIY;?></td>
    <td  style="text-align:center;"><?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->gelar_depan.' '.$model->pejabatPenilai->dataDiri->nama.' '.$model->pejabatPenilai->dataDiri->gelar_belakang : '-'?>
      <br>
      <?=!empty($model->pejabatPenilai) ? $model->pejabat_penilai : '-'?>
    </td>
  </tr>
</table>

<br><br>
<table width="100%">
  <tr>
    <td width="100%" style="font-size:0.8em">
      Catatan:<br>
      * AK bagi dosen yang memangku jabatan tertentu<br>
      * penghitungan AK (angka kredit) disesuaikan dengan penghitungan BKD (Beban Kinerja Dosen) KOPERTAIS Wilayah IV Surabaya sebagaimana terlampir<br>
      * Jumlah AK min 24 sks mak 32 sks
    </td>
  </tr>
</table>