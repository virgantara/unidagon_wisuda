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
/* @var $this yii\web\View */
/* @var $model app\models\Skp */

// $list_unsur = ArrayHelper::map(\app\models\UnsurUtama::find()->orderBy(['urutan'=>SORT_ASC])->all(),'id','nama');

// $this->title = 'Form SKP Periode '.date('d-m-Y',strtotime($model->periode->tanggal_bkd_awal)).' s/d '.date('d-m-Y',strtotime($model->periode->tanggal_bkd_akhir));
// $this->params['breadcrumbs'][] = ['label' => 'Skps', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
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
            <th width="40%">: <?=$model->pegawaiDinilai->dataDiri->gelar_depan;?> <?=$model->pegawaiDinilai->dataDiri->nama;?> <?=$model->pegawaiDinilai->dataDiri->gelar_belakang;?></th>
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
            <th>: <?=$model->pegawaiDinilai->dataDiri->namaPangkat;?></th>
        </tr>
        <tr>
            <th>Jabatan</th>
            <th>: <?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->namaJabfung : '-'?></th>
            <th>Jabatan</th>
            <th>: <?=$model->pegawaiDinilai->dataDiri->namaJabfung;?></th>
        </tr>
        <tr>
            <th>Unit Kerja</th>
            <th>: <?=!empty($model->jabatanPenilai) && !empty($model->jabatanPenilai->unker) ? $model->jabatanPenilai->unker->nama : '-'?></th>
            <th>Unit Kerja</th>
            <th>: <?=!empty($model->jabatanPegawai) && !empty($model->jabatanPegawai->unker) ? $model->jabatanPegawai->unker->nama : '-'?></th>
        </tr>
        <tr>
            <th>Status SKP</th>
            <th>: 
                <?php 
                echo '<span class="label label-'.$list_status_skp[$model->status_skp]['label'].'">'.$list_status_skp[$model->status_skp]['nama'].'</span>';
                
                 ?>
            </th>
            <th></th>
            <th></th>
        </tr>
    </tbody>
</table>

<table width="100%" border="1" cellpadding="1" cellspacing="0">
    <thead>
        <tr>
            <th rowspan="2"  width="3%">No</th>
            <th rowspan="2"  width="32%">Kegiatan</th>
            <th rowspan="2"  width="5%">AK</th>
            <th colspan="4"  width="20%" style="text-align: center;">Target</th>
            <th rowspan="2"  width="5%">AK</th>
            <th colspan="4"  width="20%" style="text-align: center;">Realisasi</th>
            <th rowspan="2"  width="7%">Penghitungan</th>
            <th rowspan="2"  width="8%">Nilai Capaian SKP</th>
        </tr>
        <tr>
            <th width="5%">Kuant/Output</th>
            <th width="5%">Kual/Mutu</th>
            <th width="5%">Waktu</th>
            <th width="5%">Biaya</th>
            <th width="5%">Kuant/Output</th>
            <th width="5%">Kual/Mutu</th>
            <th width="5%">Waktu</th>
            <th width="5%">Biaya</th>
        </tr>
        <tr>
            <?php 
            for($i=1;$i<=14;$i++)
                echo '<th style="font-size:6px;text-align:center">'.$i.'</th>';
             ?>
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
            <td width="3%"><?=$q+1;?></td>
            <td width="32%"><?=$item->nama;?></td>
            <td width="5%" style="text-align: center;"><?=$item->target_ak;?></td>
            <td width="5%" style="text-align: center;"><?=$item->target_qty;?> <?=$item->target_satuan;?></td>
            <td width="5%" style="text-align: center;"><?=$item->target_mutu;?></td>
            <td width="5%" style="text-align: center;"><?=$item->target_waktu;?> <?=$item->target_waktu_satuan;?></td>
            
            <td width="5%" style="text-align: center;"><?=MyHelper::formatRupiah($item->target_biaya);?></td>
        
            <td width="5%" style="text-align: center;">
                <?php 
                echo $item->realisasi_ak;
                 ?>
            </td>
            <td width="5%" style="text-align: center;">
                <?php 
                echo $item->realisasi_qty;
                 ?> <?=$item->realisasi_satuan;?>
                    
            </td>
            <td width="5%" style="text-align: center;">
                <?php 
                echo $item->realisasi_mutu;
                 ?> 
            </td>
            <td width="5%" style="text-align: center;">
                <?php 
                echo $item->realisasi_waktu;
                 ?> 
                <?=$item->realisasi_waktu_satuan;?></td>
            
            <td width="5%" style="text-align: center;">
                <?php 
                echo MyHelper::formatRupiah($item->realisasi_biaya);
                 ?>

            </td>
            <td width="7%" style="text-align: center;"><?=round($penghitungan,2);?></td>
            <td width="8%" style="text-align: center;"><?=round($capaian_skp,2);?></td>
            
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
    </tbody>
    <tfoot>
        <tr>
            <td colspan="13">Nilai Capaian SKP</td>
            <td >
                <?=round($avg_capaian_skp,2);?>
                (<b><?=$kesimpulan;?></b>)
            </td>
            
        </tr>
    </tfoot>
</table>
