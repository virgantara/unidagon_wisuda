<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PesertaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="peserta-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nim') ?>

    <?= $form->field($model, 'nama_lengkap') ?>

    <?= $form->field($model, 'fakultas') ?>

    <?= $form->field($model, 'prodi') ?>

    <?= $form->field($model, 'ukuran_kaos') ?>

    <?= $form->field($model, 'jumlah_rombongan') ?>

    <?php // echo $form->field($model, 'tempat_lahir') ?>

    <?php // echo $form->field($model, 'tanggal_lahir') ?>

    <?php // echo $form->field($model, 'jenis_kelamin') ?>

    <?php // echo $form->field($model, 'status_warga') ?>

    <?php // echo $form->field($model, 'warga_negara') ?>

    <?php // echo $form->field($model, 'alamat') ?>

    <?php // echo $form->field($model, 'no_telp') ?>

    <?php // echo $form->field($model, 'nama_ayah') ?>

    <?php // echo $form->field($model, 'pekerjaan_ayah') ?>

    <?php // echo $form->field($model, 'nama_ibu') ?>

    <?php // echo $form->field($model, 'pekerjaan_ibu') ?>

    <?php // echo $form->field($model, 'pas_photo') ?>

    <?php // echo $form->field($model, 'ijazah') ?>

    <?php // echo $form->field($model, 'akta_kelahiran') ?>

    <?php // echo $form->field($model, 'kwitansi_jilid') ?>

    <?php // echo $form->field($model, 'surat_bebas_pinjaman') ?>

    <?php // echo $form->field($model, 'resume_skripsi') ?>

    <?php // echo $form->field($model, 'surat_bebas_tunggakan') ?>

    <?php // echo $form->field($model, 'transkrip') ?>

    <?php // echo $form->field($model, 'skl_tahfidz') ?>

    <?php // echo $form->field($model, 'kwitansi_wisuda') ?>

    <?php // echo $form->field($model, 'tanda_keluar_asrama') ?>

    <?php // echo $form->field($model, 'surat_jalan') ?>

    <?php // echo $form->field($model, 'skripsi') ?>

    <?php // echo $form->field($model, 'abstrak') ?>

    <?php // echo $form->field($model, 'kode_pendaftaran') ?>

    <?php // echo $form->field($model, 'kampus') ?>

    <?php // echo $form->field($model, 'status_validasi') ?>

    <?php // echo $form->field($model, 'kmi') ?>

    <?php // echo $form->field($model, 'bukti_revisi_bahasa') ?>

    <?php // echo $form->field($model, 'bukti_layouter') ?>

    <?php // echo $form->field($model, 'bukti_perpus') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'periode_id') ?>

    <?php // echo $form->field($model, 'drive_path') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
