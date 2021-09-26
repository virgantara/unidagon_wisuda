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
?>
<table width="100%">
  <tr>
    
    <td style="text-align: center;">
      <span style="font-size: 1.15em">SASARAN KINERJA PEGAWAI</span><br>
      <span style="font-size: 1.15em">DAN PENILAIAN PERILAKU</span>
     
 
    </td>
  </tr>
</table>
<br><br><br><br><br><br><br><br><br><br>
<table border="0" width="100%" cellpadding="1" cellspacing="0">   
    <tr>
      <th width="40%" ><strong>Nama Dosen</strong></th>
      <th width="60%" >: <?=$nama_pegawai;?></th>
    </tr>
    <tr>
      <th width="40%" ><strong>NIY</strong></th>
      <th width="60%" >: <?=$model->pegawai_dinilai;?></th>
    </tr>
    
    <tr>
      <th width="40%" ><strong>Jabatan</strong></th>
      <th width="60%" >: <?=!empty($model->jabatanPegawai) && !empty($model->jabatanPegawai->jabatan) ? $model->jabatanPegawai->jabatan->nama: '';?></th>
    </tr>
    <tr>
      <th width="40%" ><strong>Unit Kerja</strong></th>
      <th width="60%" >: <?=!empty($model->jabatanPegawai) && !empty($model->jabatanPegawai->unker) ? $model->jabatanPegawai->unker->nama: '';?></th>
    </tr>
    <tr>
      <th width="40%" ><strong>Tahun Laporan</strong></th>
      <th width="60%" >: <?=$bkd_periode->nama_periode;?></th>
    </tr>
    
</table><br><br>