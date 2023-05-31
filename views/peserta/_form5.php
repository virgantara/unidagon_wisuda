<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

setlocale(LC_ALL, 'id_ID', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');
/* @var $this yii\web\View */
/* @var $model app\models\Peserta */
/* @var $form yii\widgets\ActiveForm */
?>
<style>

</style>
<form id="form">
    <ul id="progressbar">
        <li class="" id="step1">
            <strong>Biodata</strong>
        </li>
        <li id="step2"><strong>Data Orang Tua</strong></li>
        <li id="step3"><strong>Bukti Wisuda</strong></li>
        <li id="step4"><strong>Data Wisuda</strong></li>
        <li id="step5" class="active"><strong>Konfirmasi</strong></li>
    </ul>


</form>

<?php $form = ActiveForm::begin(); ?>
<?= $form->errorSummary($model, ['header' => '<div class="alert alert-danger">', 'footer' => '</div>']); ?>
<div class="col-lg-6 col-md-12 col-sm-12">


    <h2>Data Pribadi Calon Wisuda</h2>
    <table class="table">
        <tr>
            <td>NIM</td>
            <td><?= $model->nim ?></td>
        </tr>
        <tr>
            <td>Nama Lengkap</td>
            <td><?= $model->nama_lengkap ?></td>
        </tr>
        <tr>
            <td>Fakultas</td>
            <td><?= $model->fakultas ?></td>
        </tr>
        <tr>
            <td>Prodi</td>
            <td><?= $model->prodi ?></td>
        </tr>
        <tr>
            <td>TTL</td>
            <td><?= $model->tempat_lahir ?>, <?= strftime('%d %B %Y', strtotime($model->tanggal_lahir)); ?></td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td><?= $model->jenis_kelamin ?></td>
        </tr>
        <tr>
            <td>Status Warga</td>
            <td><?= $model->status_warga ?></td>
        </tr>
        <tr>
            <td>Negara Asal</td>
            <td><?= $model->warga_negara ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td><?= $model->alamat ?></td>
        </tr>
        <tr>
            <td>Telp</td>
            <td><?= $model->no_telp ?></td>
        </tr>
        <tr>
            <td>Nama Ayah</td>
            <td><?= $model->nama_ayah ?></td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td><?= $model->pekerjaan_ayah ?></td>
        </tr>
        <tr>
            <td>Nama Ibu</td>
            <td><?= $model->nama_ibu ?></td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td><?= $model->pekerjaan_ibu ?></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td><?= $model->alamat ?></td>
        </tr>
        <tr>
            <td>Ukuran Kaos</td>
            <td><?= $model->ukuran_kaos ?></td>
        </tr>
        <tr>
            <td>Jumlah Rombongan</td>
            <td><?= $model->jumlah_rombongan ?></td>
        </tr>
    </table>
    <?= $form->field($model, 'nim')->hiddenInput()->label(false) ?>


</div>
<div class="col-lg-6 col-md-12 col-sm-12">

    <h2>Bukti Unggahan Wisuda</h2>
    <?php
    $counter = 0;
    foreach ($list_syarat as $q => $syarat) :
        $ps = $list_bukti_peserta[$syarat->id];

        if (!empty($ps)) {
            $counter++;
            echo '<div class="form-group">';
            echo '<label>' . ($counter) . '. ' . $syarat->nama . '</label>';
            echo '<div>';
            echo Html::a('<i class="fa fa-download"></i> Unduh ' . $ps->syarat->nama, $ps->file_path, ['class' => 'btn btn-success', 'target' => '_blank']);
            echo '</div>';
            echo '</div>';
        }
    endforeach;

    if ($counter != count($list_syarat)) {
        $sisa = count($list_syarat) - $counter;
        echo '<div class="alert alert-danger"><h3>Anda belum mengunggah <b>' . $sisa . '</b> bukti syarat wisuda</h3></div>';
    }


    ?>


</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="pull-left">
                <?= Html::a('<i class="fa fa-arrow-left"></i> Prev ', ['peserta/create', 'step' => 4], ['class' => 'btn btn-default']) ?>
            </div>
            <div class="pull-right">

                <?= Html::submitButton('Finish <i class="fa fa-check"></i>', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>