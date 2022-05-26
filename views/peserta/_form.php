<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Peserta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="peserta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nim',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'nama_lengkap',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'fakultas',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'prodi',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'tempat_lahir',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'tanggal_lahir',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'jenis_kelamin',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'status_warga',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'warga_negara',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'alamat',['options' => ['tag' => false]])->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'no_telp',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'nama_ayah',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'pekerjaan_ayah',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'nama_ibu',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'pekerjaan_ibu',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'pas_photo',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'ijazah',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'akta_kelahiran',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'kwitansi_jilid',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'surat_bebas_pinjaman',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'resume_skripsi',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'surat_bebas_tunggakan',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'transkrip',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'skl_tahfidz',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'kwitansi_wisuda',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'tanda_keluar_asrama',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'surat_jalan',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'skripsi',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'abstrak',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'kode_pendaftaran',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'kampus',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'status_validasi',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'kmi',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'bukti_revisi_bahasa',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'bukti_layouter',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'bukti_perpus',['options' => ['tag' => false]])->textInput(['class'=>'form-control','maxlength' => true]) ?>

    <?= $form->field($model, 'created',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'periode_id',['options' => ['tag' => false]])->textInput() ?>

    <?= $form->field($model, 'drive_path',['options' => ['tag' => false]])->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
