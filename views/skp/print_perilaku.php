<?php
use app\helpers\MyHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$list_status_skp = MyHelper::statusSkp();
/* @var $this yii\web\View */
/* @var $model app\models\Skp */

// $list_unsur = ArrayHelper::map(\app\models\UnsurUtama::find()->orderBy(['urutan'=>SORT_ASC])->all(),'id','nama');

// $this->title = 'Form SKP Periode '.date('d-m-Y',strtotime($model->periode->tanggal_bkd_awal)).' s/d '.date('d-m-Y',strtotime($model->periode->tanggal_bkd_akhir));
// $this->params['breadcrumbs'][] = ['label' => 'Skps', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<table width="100%">
  <tr>
    
    <td style="text-align: center;">
      <span style="font-size: 1.15em">CATATAN PENILAIAN PERILAKU PEGAWAI</span><br>
      <span style="font-size: 1.15em">UNIVERSITAS DARUSSALAM GONTOR</span><br>
    </td>
  </tr>
</table>
<br><br>
<table width="100%" cellpadding="3" cellspacing="0">
    <tbody>
        
        <tr>
            <th width="30%">Nama</th>
            <th width="70%">: <?=$model->pegawaiDinilai->dataDiri->gelar_depan;?> <?=$model->pegawaiDinilai->dataDiri->nama;?> <?=$model->pegawaiDinilai->dataDiri->gelar_belakang;?></th>
        </tr>
        <tr>
            <th width="30%">NIY</th>
            <th width="70%">: <?=$model->pegawai_dinilai;?></th>
        </tr>
        <tr>
            <th width="30%">Periode</th>
            <th width="70%">: <?=$bkd_periode->nama_periode;?></th>
        </tr>
        
    </tbody>
</table>
<br><br>

<table width="100%" cellpadding="3" cellspacing="0" border="0">
    <tbody>

        <tr>
            <th width="30%" style="border-top:solid 1px black;;border-left:solid 1px black;;border-bottom:solid 1px black">a. Sasaran Kerja Pegawai (SKP)</th>
            <th width="30%" style="border-top:solid 1px black;border-bottom:solid 1px black"></th>
            <th width="10%" style="text-align:center;border-top:solid 1px black;border-bottom:solid 1px black"></th>
            <th width="20%" style="text-align:center;border-top:solid 1px black;border-bottom:solid 1px black"></th>
            <th width="10%" style="text-align:center;border-top:solid 1px black;border-bottom:solid 1px black;border-left:solid 1px black;border-right:solid 1px black"><?=round($bobot_capaian_skp,2);?></th>
        </tr>
        <tr>
            <th style=";border-left:solid 1px black">b. Perilaku Kerja</th>
            <th style=";border-left:solid 1px black">1. Orientasi Pelayanan</th>
            <th style="text-align:center;;border-left:solid 1px black"><?=!empty($skpPerilaku) ? $skpPerilaku->orientasi : null;?></th>
            <th style="text-align:center;border-left:solid 1px black"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->orientasi : 0)?></th>
            <th style="border-left:solid 1px black;border-right:solid 1px black"></th>
        </tr>
        <tr>
            <th style=";border-left:solid 1px black"></th>
            <th style=";border-left:solid 1px black">2. Integritas</th>
            <th style="text-align:center;border-left:solid 1px black"><?=!empty($skpPerilaku) ? $skpPerilaku->integritas : null?>
            </th>
            <th style="text-align:center;border-left:solid 1px black"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->integritas : 0)?></th>
            <th style="border-left:solid 1px black;border-right:solid 1px black"></th>
        </tr>
        <tr>
            <th style=";border-left:solid 1px black"></th>
            <th style=";border-left:solid 1px black">3. Komitmen</th>
            <th style="text-align:center;border-left:solid 1px black"><?=!empty($skpPerilaku) ? $skpPerilaku->komitmen : null?>
            </th>
            <th style="text-align:center;border-left:solid 1px black"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->komitmen : 0)?></th>
            <th style="border-left:solid 1px black;border-right:solid 1px black"></th>
        </tr>
        <tr>
            <th style=";border-left:solid 1px black"></th>
            <th style=";border-left:solid 1px black">4. Disiplin</th>
            <th style="text-align:center;border-left:solid 1px black"><?=!empty($skpPerilaku) ? $skpPerilaku->disiplin : null?></th>
            <th style="text-align:center;border-left:solid 1px black"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->disiplin : 0)?></th>
            <th style="border-left:solid 1px black;border-right:solid 1px black"></th>
        </tr>
        <tr>
            <th style=";border-left:solid 1px black"></th>
            <th style=";border-left:solid 1px black">5. Kerjasama</th>
            <th style="text-align:center;border-left:solid 1px black"><?=!empty($skpPerilaku) ? $skpPerilaku->kerjasama : null?></th>
            <th style="text-align:center;border-left:solid 1px black"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->kerjasama : 0)?></th>
            <th style="border-left:solid 1px black;border-right:solid 1px black"></th>
        </tr>
        <tr>
            <th style=";border-left:solid 1px black"></th>
            <th style=";border-left:solid 1px black;border-bottom:solid 1px black">6. Kepemimpinan</th>
            <th style="text-align:center;border-left:solid 1px black;border-bottom:solid 1px black"><?=!empty($skpPerilaku) ? $skpPerilaku->kepemimpinan : null?></th>
            <th style="text-align:center;border-left:solid 1px black;border-bottom:solid 1px black"><?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->kepemimpinan : 0)?></th>
            <th style=";border-bottom:solid 1px black;border-left:solid 1px black;border-right:solid 1px black"></th>
        </tr>
        <tr>
            <th style=";border-left:solid 1px black"></th>
            <th style=";border-left:solid 1px black">Jumlah</th>
            <th style="text-align:center;border-left:solid 1px black"><?=!empty($skpPerilaku) ? $skpPerilaku->total : 0;?></th>
            <th style=";border-left:solid 1px black"><label id="label_jumlah_persen"></label></th>
            <th style="border-left:solid 1px black;border-right:solid 1px black"></th>
        </tr>
        <tr>
            <th style=";border-left:solid 1px black"></th>
            <th style=";border-left:solid 1px black">Nilai rata-rata</th>
            <th style="text-align:center;border-left:solid 1px black"><?=!empty($skpPerilaku) ? round($skpPerilaku->rata_rata,2) : 0;?></th>
            <th style="text-align:center;border-left:solid 1px black">(<?= MyHelper::kesimpulan(!empty($skpPerilaku) ? $skpPerilaku->rata_rata : 0)?>)</th>
            <th style="border-left:solid 1px black;border-right:solid 1px black"></th>
        </tr>
        <tr>
            <th style=";border-left:solid 1px black"></th>
            <th style=";border-left:solid 1px black">Nilai Perilaku kerja</th>
            <th style="text-align:center;border-left:solid 1px black"><?=!empty($skpPerilaku) ? round($skpPerilaku->rata_rata,2) : 0;?></th>
            <th style="text-align:center;border-left:solid 1px black"></th>
            <th style="text-align:center;border-left:solid 1px black;border-right:solid 1px black;border-right:solid 1px black"><?=!empty($skpPerilaku) ? round($skpPerilaku->rata_rata * 0.4,2) : 0;?></th>
        </tr>
        <tr>
            <th  style=";border-left:solid 1px black;border-top:solid 1px black;border-bottom:solid 1px black" rowspan="2" colspan="4">Nilai Prestasi Kerja</th>
            <th style="text-align:center;border-top:solid 1px black;border-bottom:solid 1px black;border-left:solid 1px black;border-right:solid 1px black"><?=round($bobot_capaian_skp + $bobot_avg_perilaku,2)?></th>
            <!-- <th>b</th> -->
        </tr>
        <tr>
            <th  style="text-align:center;border-left:solid 1px black;border-top:solid 1px black;border-bottom:solid 1px black;border-right:solid 1px black" >(<?= MyHelper::kesimpulan($total_prestasi)?>)</th>
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
        Atasan Pejabat Penilai,
        
        <br>
        <br>
        <br>
        <br>
        
        <u><?=!empty($model->atasanPejabatPenilai->dataDiri) ? $model->atasanPejabatPenilai->dataDiri->gelar_depan.' '.$model->atasanPejabatPenilai->dataDiri->nama.' '.$model->atasanPejabatPenilai->dataDiri->gelar_belakang : '-'?></u>
        <br>
        NIY: <?=!empty($model->atasanPejabatPenilai) ? $model->atasan_pejabat_penilai : '-'?>
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
        <u><?=$model->pegawaiDinilai->dataDiri->gelar_depan;?> <?=$model->pegawaiDinilai->dataDiri->nama;?> <?=$model->pegawaiDinilai->dataDiri->gelar_belakang;?></u>
        <br>
        NIY: <?=$model->pegawaiDinilai->NIY;?>
      </th>
    </tr>
    
</table>

