<?php
use app\helpers\MyHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


setlocale(LC_ALL, 'id_ID', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');

$tgl_awal = strftime('%d %B %Y',strtotime($periode->tanggal_skp_awal));
$tgl_akhir = strftime('%d %B %Y',strtotime($periode->tanggal_skp_akhir));

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

?>
<table width="100%" border="0">
    <tr>
        <td width="47%">
            
            <table width="100%" border="1" cellpadding="2" cellspacing="0">
                <tr>
                    <td width="100%">
                        
            8. REKOMENDASI
            <br><br><br><br><br><br><br><br><br><br><br><br><br>
             
                    </td>
                </tr>
                <tr>
                    <td>

                        <table width="100%" border="0" cellpadding="2" cellspacing="0">
                            <tr>
                                <td width="50%">
                                    
                                </td>
                                <td width="50%" style="text-align: center;">
                                    9. DIBUAT TANGGAL 
                                    <?php 
                                    $tgl = date('Y-m-d',strtotime('+4 day',strtotime($periode->tanggal_skp_awal)));
                                    echo strtoupper(strftime('%d %B %Y',strtotime($tgl)));
                                     ?>

                                     <br>PEJABAT PENILAI
                                     <br><br><br><br><br>
                                     <u><?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->gelar_depan.' '.$model->pejabatPenilai->dataDiri->nama.' '.$model->pejabatPenilai->dataDiri->gelar_belakang : '-'?></u>
                                    <br>
                                    NIY: <?=!empty($model->pejabatPenilai) ? $model->pejabat_penilai : '-'?>
                                    <br><br>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="text-align: center;">
                                    10. DITERIMA TANGGAL 
                                    <?php 
                                    $tgl = date('Y-m-d',strtotime('+6 day',strtotime($periode->tanggal_skp_awal)));
                                    echo strtoupper(strftime('%d %B %Y',strtotime($tgl)));
                                     ?>

                                     <br>DOSEN PTKIS
                                     <br><br><br><br><br>
                                    <u><?=$nama_pegawai;?></u>
                                    <br>
                                    NIY: <?=$model->pegawaiDinilai->NIY;?>
                                    <br><br>
                                </td>
                                <td width="50%" style="text-align: right;">
                                    
                                </td>
                            </tr>
                            <tr>
                                <td width="50%" style="text-align: center;">
                                    
                                </td>
                                <td width="50%" style="text-align: center;">
                                    11. DITERIMA TANGGAL 
                                    <?php 
                                    $tgl = date('Y-m-d',strtotime('+9 day',strtotime($periode->tanggal_skp_awal)));
                                    echo strtoupper(strftime('%d %B %Y',strtotime($tgl)));
                                     ?>

                                     <br>ATASAN PEJABAT PENILAI
                                     <br><br><br><br><br>
                                    <u><?=!empty($atasanPejabatPenilai->dataDiri) ? $atasanPejabatPenilai->dataDiri->gelar_depan.' '.$atasanPejabatPenilai->dataDiri->nama.' '.$atasanPejabatPenilai->dataDiri->gelar_belakang : '-'?></u>
                                    <br>
                                    NIY: <?=!empty($atasanPejabatPenilai) ? $atasanPejabatPenilai->NIY : ''?>
                                </td>
                            </tr>
                        </table>

             
                    </td>
                </tr>
            </table>


        </td>
        <td width="4%"></td>
        <td width="47%">
            <br><br><br><br><br><br>
            <table width="100%">
              <tr>
                
                <td style="text-align: center;">
                  <span style="font-size: 1em;font-weight: bold;">PENILAIAN PRESTASI KINERJA</span><br>
                  <span style="font-size: 1em;font-weight: bold;">DOSEN PTKIS</span>
                 
             
                </td>
              </tr>
            </table><br><br>

            <table width="100%">
              <tr>
                  <td width="50%">KEMENTERIAN AGAMA RI</td>
                  <td width="50%">JANGKA WAKTU PENILAIAN</td>
              </tr>
              <tr>
                  <td width="50%">UNIVERSITAS DARUSSALAM GONTOR</td>
                  <td width="50%" style="font-size:0.9em">BULAN : <?=$tgl_awal;?> s/d <?=$tgl_akhir;?></td>
              </tr>
            </table>
            <table width="100%" border="1" cellpadding="5">
              <tr>
                  <td rowspan="6" width="5%">1</td>
                  <td colspan="2" width="95%">YANG DINILAI</td>
              </tr>
              <tr>
                  <td width="40%">a. Nama</td>
                  <td width="55%"><?=$nama_pegawai;?></td>
              </tr>
              <tr>
                  <td width="40%">b. NIY</td>
                  <td width="55%"><?=$model->pegawai_dinilai;?></td>
              </tr>
              <tr>
                  <td width="40%">c. Pangkat, Golongan Ruang, TMT</td>
                  <td width="55%"><?=!empty($user->dataDiri->pangkat0) ? $user->dataDiri->pangkat0->nama.' - '.$user->dataDiri->pangkat0->golongan: '-';?></td>
              </tr>
              <tr>
                  <td width="40%">d. Jabatan/Pekerjaan</td>
                  <td width="55%"><?=!empty($user->dataDiri->jabatanFungsional) ? $user->dataDiri->jabatanFungsional->nama : '-';?> / <?=!empty($model->jabatanPegawai) && !empty($model->jabatanPegawai->jabatan) ? $model->jabatanPegawai->jabatan->nama: '';?></td>
              </tr>
              <tr>
                  <td width="40%">e. Unit Organisasi</td>
                  <td width="55%"><?=!empty($model->jabatanPegawai) && !empty($model->jabatanPegawai->unker) ? $model->jabatanPegawai->unker->nama: '';?></td>
              </tr>
              
              <tr>
                  <td rowspan="6" width="5%">2</td>
                  <td colspan="2" width="95%">PEJABAT PENILAI</td>
              </tr>
              <tr>
                  <td width="40%">a. Nama</td>
                  <td width="55%"><?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->gelar_depan.' '.$model->pejabatPenilai->dataDiri->nama.' '.$model->pejabatPenilai->dataDiri->gelar_belakang : '-'?></td>
              </tr>
              <tr>
                  <td width="40%">b. NIY</td>
                  <td width="55%"><?=!empty($model->pejabatPenilai) ? $model->pejabat_penilai : '-'?></td>
              </tr>
              <tr>
                  <td width="40%">c. Pangkat, Golongan Ruang, TMT</td>
                  <td width="55%"><?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->namaPangkat : '-'?></td>
              </tr>
              <tr>
                  <td width="40%">d. Jabatan/Pekerjaan</td>
                  <td width="55%"><?=!empty($model->pejabatPenilai->dataDiri) ? $model->pejabatPenilai->dataDiri->namaJabfung : '-'?></td>
              </tr>
              <tr>
                  <td width="40%">e. Unit Organisasi</td>
                  <td width="55%"><?=!empty($model->jabatanPenilai) && !empty($model->jabatanPenilai->unker) ? $model->jabatanPenilai->unker->nama : '-'?></td>
              </tr>
              <tr>
                  <td rowspan="6" width="5%">3</td>
                  <td colspan="2" width="95%">ATASAN PEJABAT PENILAI</td>
              </tr>
              <tr>
                  <td width="40%">a. Nama</td>
                  <td width="55%"><?=!empty($atasanPejabatPenilai->dataDiri) ? $atasanPejabatPenilai->dataDiri->gelar_depan.' '.$atasanPejabatPenilai->dataDiri->nama.' '.$atasanPejabatPenilai->dataDiri->gelar_belakang : '-'?></td>
              </tr>
              <tr>
                  <td width="40%">b. NIY</td>
                  <td width="55%"><?=!empty($atasanPejabatPenilai) ? $atasanPejabatPenilai->NIY : '-'?></td>
              </tr>
              <tr>
                  <td width="40%">c. Pangkat, Golongan Ruang, TMT</td>
                  <td width="55%"><?=!empty($atasanPejabatPenilai->dataDiri) ? $atasanPejabatPenilai->dataDiri->namaPangkat : '-'?></td>
              </tr>
              <tr>
                  <td width="40%">d. Jabatan/Pekerjaan</td>
                  <td width="55%"><?=!empty($atasanPejabatPenilai->dataDiri) ? $atasanPejabatPenilai->dataDiri->namaJabfung : '-'?></td>
              </tr>
              <tr>
                  <td width="40%">e. Unit Organisasi</td>
                  <td width="55%"><?=!empty($jabatanAtasanPenilai) && !empty($jabatanAtasanPenilai->unker) ? $jabatanAtasanPenilai->unker->nama : '-'?></td>
              </tr>
            </table>
        </td>
    </tr>
</table>