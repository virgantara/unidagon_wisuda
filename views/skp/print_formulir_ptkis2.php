<?php
use app\helpers\MyHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


setlocale(LC_ALL, 'id_ID', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');

$tgl_awal = strftime('%d %B %Y',strtotime($bkd_periode->tanggal_bkd_awal));
$tgl_akhir = strftime('%d %B %Y',strtotime($bkd_periode->tanggal_bkd_akhir));

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


$font_size = '0.9em';
?>
<table width="100%" border="0">
    <tr>
        <td width="45%">
            
           <table width="100%" border="1" cellpadding="4">

  <tr>
    <td rowspan="11" width="5%">4</td>
    <td colspan="4" width="80%">UNSUR YANG DINILAI</td>
    <td width="15%">Jumlah</td>
  </tr>
  <tr>
    <td colspan="4">a. Sasaran Kinerja Pegawai&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;92 x 60%</td>
    <td>55.20</td>
  </tr>
  <tr>
    <td rowspan="9" width="25%">b. Perilaku Kerja</td>
    <td  width="28%" style="font-size:<?=$font_size;?>">1. Orientasi Pelayanan</td>
    <td  width="10%">76</td>
    <td  width="17%">Baik sekali</td>
    <td width="15%"></td>
  </tr>
  <tr>
    <td style="font-size:<?=$font_size;?>">2. Integritas</td>
    <td>76</td>
    <td>Baik sekali</td>
    <td></td>
  </tr>
  <tr>
    <td style="font-size:<?=$font_size;?>">3. Komitmen</td>
    <td>76</td>
    <td>Baik sekali</td>
    <td></td>
  </tr>
  <tr>
    <td style="font-size:<?=$font_size;?>">4. Disiplin</td>
    <td>76</td>
    <td>Baik sekali</td>
    <td></td>
  </tr>
  <tr>
    <td  style="font-size:<?=$font_size;?>">5. Kerjasama</td>
    <td >76</td>
    <td>Baik sekali</td>
    <td></td>
  </tr>
  <tr>
    <td style="font-size:<?=$font_size;?>">6. Kepemimpinan</td>
    <td>76</td>
    <td>Baik sekali</td>
    <td></td>
  </tr>
  <tr>
    <td style="font-size:<?=$font_size;?>">7. Jumlah</td>
    <td>76</td>
    <td>Baik sekali</td>
    <td></td>
  </tr>
  <tr>
    <td style="font-size:<?=$font_size;?>">8. Nilai rata-rata</td>
    <td>63.33</td>
    <td>Cukup<br></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3">9. Nilai Perilaku Kerja&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;63.33 x 40%</td>
    <td>25.33</td>
  </tr>


  <tr>
    <td colspan="5"  style="text-align: center;" rowspan="2">NILAI PRESTASI KERJA</td>
    <td>80.53</td>
  </tr>
  <tr>
    <td>Baik</td>
  </tr>

</table>


        </td>
        <td width="5%"></td>
        <td width="45%">
            
        </td>
    </tr>
</table>