<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BimbinganMahasiswa */

$this->title = $model->judul;
$this->params['breadcrumbs'][] = ['label' => 'Bimbingan Mahasiswas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="block-header">
    <h2><?= Html::encode($this->title) ?></h2>
</div>
<div class="row">
   <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h2><?= Html::encode($this->title) ?></h2>
            </div>

            <div class="panel-body ">
        
<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            
            'judul',
            'jenis_bimbingan',
            'program_studi',
            'semester',
            'lokasi',
            'sk_penugasan',
            'tanggal_sk_penugasan',
            'keterangan',
            'komunal',
            'sister_id',
            'updated_at',
            'created_at',
        ],
    ]) ?>

            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                
                    <h3>Dosen Pembimbing</h3>
                
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Urutan</th>
                            <th>Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach($model->bimbinganMahasiswaDosens as $q => $dosen)
                        {
                            
                        
                        ?>
                        <tr>
                            <td><?=$q+1;?></td>
                            <td><?=$dosen->nama;?></td>
                            <td><?=$dosen->urutan;?></td>
                            <td><?=$dosen->kategori_kegiatan;?></td>
                        </tr>
                        <?php 
                        }
                        ?>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                
                    <h3>Mahasiswa </h3>
                
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Peran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach($model->bimbinganMahasiswaMahasiswas as $q => $mhs)
                        {
                            
                        
                        ?>
                        <tr>
                            <td><?=$q+1;?></td>
                            <td><?=$mhs->nomor_induk;?></td>
                            <td><?=$mhs->nama;?></td>
                            <td><?=$mhs->peran;?></td>
                        </tr>
                        <?php 
                        }
                        ?>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</div>