<?php

use app\helpers\MyHelper;
use app\models\Approver;
use kartik\select2\Select2;
use richardfan\widget\JSRegister;
use wdmg\helpers\ArrayHelper;
use yii\helpers\Html;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Peserta */
/* @var $form yii\widgets\ActiveForm */

setlocale(LC_ALL, 'id_ID', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');

?>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Data & Bukti Wisuda</h3>
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row">
                        <?php if (Yii::$app->user->can('admin')) :

                            $disabled = $sisa > 0;
                            $title = $sisa > 0 ? 'Tidak bisa diapprove karena bukti belum lengkap' : 'Approval';
                            $approve_btn = Html::a('<i class="fa fa-check"></i> Approve', 'javascript:void(0)', ['class' => 'btn btn-success', 'id' => 'btn-approve', 'disabled' => $disabled, 'title' => $title]);

                            $list_approver = Select2::widget([
                                'name' => 'approved_by',
                                'data' => ArrayHelper::map(Approver::find()->all(), 'id', 'nama'),
                                'value' => $model->ukuran_kaos,
                                'options' => [
                                    'class' => 'form-control',
                                    'prompt' => 'please choose an approver',
                                ],
                            ]);
                        ?>

                            <div class="col-md-1"><?= $approve_btn ?></div>
                            <div class="col-md-5">
                                <form action="" id="approverForm">
                                    <?= $list_approver ?>
                                    <?= Html::hiddenInput('id', $model->id, ['id' => 'peserta_id']); ?>
                                </form>
                            </div>

                        <?php
                        endif;
                        ?>
                        <div class="col-md-1">
                            <?= Html::a('<i class="fa fa-file-pdf-o"></i> Cetak Bukti', ['peserta/cetak-bukti-persyaratan', 'peserta_id' => $model->id], ['class' => 'btn btn-danger', 'target' => '_blank']) ?>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <div class="col-md-1"><button class="btn btn-danger" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-ban"></i> Reject</button>

                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambahkan Catatan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="modal-body">
                            <?= $form->field($model, 'catatan')->widget(CKEditor::classname(),[
                                'options' => ['rows' => 6],
                                'preset' => 'advance', 
                                ]);
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btn-catatan">Save changes</button>
                        </div>
                        <?php ActiveForm::end(); ?>
    
                        </div>
                    </div>
                    </div>
                </div>
                <br>
                <br>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <h2>Data Pribadi Calon Wisudawan</h2>
                    <table class="table table-striped">
                        <tr>
                            <td width="25%" rowspan="5">
                                <img width="128px" src="<?= !empty($results['items']['foto_path']) ? $results['items']['foto_path'] : null ?>" alt="Foto Profil">
                            </td>
                            <td width="25%" style="padding-left: 25px;"> NIM</td>
                            <td>: <?= $model->nim ?></td>
                        </tr>

                        <tr>
                            <td>Nama Lengkap</td>
                            <td>: <?= $model->nama_lengkap ?></td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>: <?= !empty($results['items']['ktp']) ? $results['items']['ktp'] : null ?></td>
                        </tr>
                        <tr>
                            <td>TTL</td>
                            <td>: <?= $model->tempat_lahir ?>, <?= strftime('%d %B %Y', strtotime($model->tanggal_lahir)); ?></td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>: <?= $model->jenis_kelamin ?></td>
                        </tr>
                        <tr>
                            <td>Kampus</td>
                            <td colspan="2">: <?= $model->kampus != null ? MyHelper::getKampus($model->kampus)  : "";?></td>
                        </tr>
                        <tr>
                            <td>Fakultas</td>
                            <td colspan="2">: <?= $model->fakultas ?></td>
                        </tr>
                        <tr>
                            <td>Prodi</td>
                            <td colspan="2">: <?= $model->prodi ?></td>
                        </tr>

                        <tr>
                            <td>Status Warga</td>
                            <td colspan="2">: <?= $model->status_warga ?></td>
                        </tr>
                        <tr>
                            <td>Negara Asal</td>
                            <td colspan="2">: <?= $model->warga_negara ?></td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td colspan="2">: <?= $model->alamat ?></td>
                        </tr>
                        <tr>
                            <td>Telp</td>
                            <td colspan="2">: <?= $model->no_telp ?></td>
                        </tr>
                        <tr>
                            <td>Nama Ayah</td>
                            <td colspan="2">: <?= $model->nama_ayah ?></td>
                        </tr>
                        <tr>
                            <td>Pekerjaan</td>
                            <td colspan="2">: <?= $model->pekerjaan_ayah ?></td>
                        </tr>
                        <tr>
                            <td>Nama Ibu</td>
                            <td colspan="2">: <?= $model->nama_ibu ?></td>
                        </tr>
                        <tr>
                            <td>Pekerjaan</td>
                            <td colspan="2">: <?= $model->pekerjaan_ibu ?></td>
                        </tr>
                        <tr>
                            <td>Alamat Email</td>
                            <td colspan="2">: <?= MyHelper::getEmailUser($model->nim) ?></td>
                        </tr>
                        <tr>
                            <td>Ukuran Kaos</td>
                            <td colspan="2">: <?= $model->ukuran_kaos ?></td>
                        </tr>
                        <tr>
                            <td>Jumlah Rombongan Wisuda</td>
                            <td colspan="2">: <?= $model->jumlah_rombongan ?></td>
                        </tr>

                        <tr>
                            <td>Verifikator</td>
                            <td colspan="2">: <?= $model->approved_by == '' ? 'Tidak ada' : $model->approvedBy->nama ?></td>
                        </tr>

                        <tr>
                            <td>Catatan</td>
                            <td colspan="2">: <?= $model->catatan == '' ? 'Tidak ada' : $model->catatan ?></td>
                        </tr>
                    </table>

                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">

                    <h2>Bukti Unggahan Wisuda</h2>
                    <?php
                    $counter = 0;
                    foreach ($list_syarat as $q => $syarat) :
                        $ps = !empty($list_bukti_peserta[$syarat->id]) ? $list_bukti_peserta[$syarat->id] : null;

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

                    if ($sisa > 0) {

                        echo '<div class="alert alert-danger"><h3>Data bukti wisuda belum lengkap. Belum diunggah: <b>' . $sisa . '</b> dokumen</h3></div>';
                    }

                    ?>

                </div>

            </div>
        </div>
    </div>
</div>

<?php JSRegister::begin() ?>
<script>
    $(document).on("click", "#btn-approve", function(e) {
        e.preventDefault()

        var obj = $("#approverForm").serialize()

        console.log(obj);

        $.ajax({
            url: "/peserta/ajax-add-approver",
            type: "POST",
            async: true,
            data: obj,
            error: function(e) {
                console.log(e.responseText)
            },
            success: function(data) {

                var hasil = $.parseJSON(data)

                if (hasil.code == 200) {

                    Swal.fire({
                        width: '64em',
                        title: 'Validate this registration?',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, validate!',
                        showLoaderOnConfirm: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var obj = new Object;
                            obj.nim = <?= $model->nim ?>;
                            $.ajax({
                                type: 'POST',
                                data: {
                                    dataPost: obj
                                },
                                url: '/peserta/ajax-approve',
                                async: true,
                                beforeSend: function() {
                                    Swal.fire({
                                        title: "Please wait",
                                        html: "Processing your request",
                                        allowOutsideClick: false,
                                        showConfirmButton: false,
                                        onBeforeOpen: () => {
                                            Swal.showLoading()
                                        },

                                    })
                                },
                                error: function(e) {
                                    Swal.fire({
                                        title: 'Oops!',
                                        icon: 'error',
                                        text: e.responseText
                                    });
                                },
                                success: function(data) {

                                    var res = $.parseJSON(data);
                                    if (res.code == 200) {
                                        Swal.fire({
                                            title: 'Yeay!',
                                            icon: 'success',
                                            text: res.message
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Oops!',
                                            icon: 'error',
                                            text: res.message
                                        });
                                    }
                                }
                            })
                        }
                    })

                } else {
                    Swal.fire({
                        title: 'Oops!',
                        icon: 'error',
                        text: hasil.message
                    })
                }
            }
        })


    })

    $(document).on("click", "#btn-catatan", function(e) {
    e.preventDefault();

    // Ambil nilai dari CKEditor
    var catatanValue = CKEDITOR.instances['peserta-catatan'].getData();

    var obj = {
        user_id: '<?=$model->id ?>',
        catatan: catatanValue // Nilai dari CKEditor
    };

    $.ajax({
        type: 'POST',
        data: {
            dataPost: obj
        },
        url: '<?= Url::to(['peserta/add-catatan']) ?>',
        async: true,
        beforeSend: function() {
            Swal.fire({
                title: "Please wait",
                html: "Processing your request",
                allowOutsideClick: false,
                showConfirmButton: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });
        },
        error: function(xhr, status, error) {
            Swal.fire({
                title: 'Oops!',
                icon: 'error',
                text: xhr.responseText
            });
        },
        success: function(data) {
            try {
                var res = $.parseJSON(data);
                if (res.code == 200) {
                    Swal.fire({
                        title: 'Yeay!',
                        icon: 'success',
                        text: res.message
                    }).then((result) => {
            if (result.value) {
              location.reload(); 
            }
          });;
                } else {
                    Swal.fire({
                        title: 'Oops!',
                        icon: 'error',
                        text: res.message
                    });
                }
            } catch (e) {
                console.error('Error parsing response: ', e);
                Swal.fire({
                    title: 'Oops!',
                    icon: 'error',
                    text: 'Error parsing server response'
                });
            }
        }
    });
});


</script>
<?php JSRegister::end() ?>