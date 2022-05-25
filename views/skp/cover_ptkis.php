<?php 

$list_staf = \app\helpers\MyHelper::listRoleStaf();
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

$tgl_awal = strftime('%d %B',strtotime($periode->tanggal_skp_awal));
$tgl_akhir = strftime('%d %B %Y',strtotime($periode->tanggal_skp_akhir));
?>
<br><br><br><br><br><br><br><br><br><br>
<table width="100%">
  <tr>
    
    <td style="text-align: center;">
      <span style="font-size: 1.3em;font-weight: bold;">PENILAIAN PRESTASI KINERJA</span><br>
      <span style="font-size: 1.3em;font-weight: bold;">DOSEN PTKIS</span>
     
 
    </td>
  </tr>
</table>
<br><br><br><br><br>
<table width="100%">
  <tr>
    
    <td style="text-align: center;">
      <span style="font-size: 1.15em;font-weight: bold;">Jangka Waktu Penilaian</span><br>
      <span style="font-size: 1.15em;font-weight: bold;"><?=$tgl_awal;?> s/d <?=$tgl_akhir;?></span>
     
 
    </td>
  </tr>
</table>
<br><br>

<table border="0" width="100%" cellpadding="1" cellspacing="0">   
    <tr>
      <th width="32%" ><strong>Nama Dosen</strong></th>
      <th width="60%" >: <?=$nama_pegawai;?></th>
    </tr>
    <tr>
      <th width="32%" ><strong>NIP/NIDN/NIY</strong></th>
      <th width="60%" >: <?=$model->pegawai_dinilai;?></th>
    </tr>
    <tr>
      <th width="32%" ><strong>Pangkat Golongan Ruang</strong></th>
      <th width="60%" >: <?=!empty($user->dataDiri->pangkat0) ? $user->dataDiri->pangkat0->nama.' - '.$user->dataDiri->pangkat0->golongan: '-';?></th>
    </tr>
    <tr>
      <th width="32%" ><strong>Jabatan</strong></th>
      <th width="60%" >: <?=!empty($user->dataDiri->jabatanFungsional) ? $user->dataDiri->jabatanFungsional->nama : '-';?> / <?=!empty($model->jabatanPegawai) && !empty($model->jabatanPegawai->jabatan) ? $model->jabatanPegawai->jabatan->nama: '';?></th>
    </tr>
    <tr>
      <th width="32%" ><strong>Unit Kerja</strong></th>
      <th width="60%" >: <?=!empty($model->jabatanPegawai) && !empty($model->jabatanPegawai->unker) ? $model->jabatanPegawai->unker->nama: '';?></th>
    </tr>

</table><br><br>

<br><br><br><br><br><br><br><br><br><br><br>
<table width="100%">
  <tr>
    
    <td style="text-align: center;">
      <span style="font-size: 1em;font-weight: bold;">KEMENTERIAN AGAMA RI</span><br>
      <span style="font-size: 1em;font-weight: bold;">UNIDA GONTOR PONOROGO</span><br>
      <span style="font-size: 1em;font-weight: bold;">TAHUN <?=date('Y',strtotime($periode->tanggal_skp_akhir))?></span>
 
    </td>
  </tr>
</table>