<?php 
use yii\helpers\ArrayHelper;

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
      <th width="60%" >: <?=$user->nama;?></th>
    </tr>
    <tr>
      <th width="40%" ><strong>NIY</strong></th>
      <th width="60%" >: <?=$user->NIY;?></th>
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