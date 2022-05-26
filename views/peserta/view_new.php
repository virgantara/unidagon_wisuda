<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Peserta */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Data & Bukti Wisuda</h3>
            </div>
            <div class="panel-body">
                
<div class="col-lg-6 col-md-12 col-sm-12">

    
    <h2>Data Pribadi Calon Wisuda</h2>
    <table class="table">
        <tr>
            <td>NIM</td>
            <td><?=$model->nim?></td>
        </tr>
        <tr>
            <td>Nama Lengkap</td>
            <td><?=$model->nama_lengkap?></td>
        </tr>
        <tr>
            <td>Fakultas</td>
            <td><?=$model->fakultas?></td>
        </tr>
        <tr>
            <td>Prodi</td>
            <td><?=$model->prodi?></td>
        </tr>
        <tr>
            <td>TTL</td>
            <td><?=$model->tempat_lahir?>, <?=$model->tanggal_lahir?></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td><?=$model->jenis_kelamin?></td>
        </tr>
        <tr>
            <td>Status Warga</td>
            <td><?=$model->status_warga?></td>
        </tr>
        <tr>
            <td>Negara Asal</td>
            <td><?=$model->warga_negara?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td><?=$model->alamat?></td>
        </tr>
        <tr>
            <td>Telp</td>
            <td><?=$model->no_telp?></td>
        </tr>
        <tr>
            <td>Nama Ayah</td>
            <td><?=$model->nama_ayah?></td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td><?=$model->pekerjaan_ayah?></td>
        </tr>
        <tr>
            <td>Nama Ibu</td>
            <td><?=$model->nama_ibu?></td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td><?=$model->pekerjaan_ibu?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td><?=$model->alamat?></td>
        </tr>
    </table>

</div>
<div class="col-lg-6 col-md-12 col-sm-12">

        <h2>Bukti Unggahan Wisuda</h2>
        <?php 
        $counter = 0;
        foreach($list_syarat as $q => $syarat):
            $ps = $list_bukti_peserta[$syarat->id];
        
            if(!empty($ps)){
                $counter++;
                echo '<div class="form-group">';
                echo '<label>'.($counter).'. '.$syarat->nama.'</label>';
                echo '<div>';
                echo Html::a('<i class="fa fa-download"></i> Unduh '.$ps->syarat->nama,$ps->file_path,['class'=>'btn btn-success','target'=>'_blank']);
                echo '</div>';
                echo '</div>';
            }
        endforeach;

        if($counter != count($list_syarat)){
            $sisa = count($list_syarat) - $counter;
            echo '<div class="alert alert-danger"><h3>Anda belum mengunggah <b>'.$sisa.'</b> bukti syarat wisuda</h3></div>';

        }


         ?>
    
   
</div>

            </div>
        </div>
    </div>
</div>