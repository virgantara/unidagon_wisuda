<?php

use app\helpers\MyHelper;

?>
<table width="100%" style="padding: 5px;">
    <tr>
        <td width="35%" style="text-align: right;" rowspan="2">
            
        </td>
        <td>
            <span style="font-size: 1.1em">Persyaratan Pendaftaran Wisuda</span>
        </td>
    </tr>
    <tr>
        <td>
            <span style="font-size: 1.1em">Universitas Darussalam Gontor</span>
        </td>
    </tr>
</table>
<br><br><br>
<table width="100%" style="padding: 4px;">
    <tr>
        <td width="15%">NIM</td>
        <td width="28%">: <?= $peserta->nim ?></td>
        <td width="17%">Nama Lengkap</td>
        <td width="40%">: <?= $peserta->nama_lengkap ?></td>
    </tr>
    <tr>
        <td>NIK</td>
        <td>: <?= $peserta->nik ?></td>
        <td>Program Studi</td>
        <td>: <?= $peserta->prodi ?></td>
    </tr>
    <tr>
        <td>TTL</td>
        <td>: <?= $peserta->tempat_lahir ?>, <?= strftime('%d %B %Y', strtotime($peserta->tanggal_lahir)); ?></td>
        <td>Jenis Kelamin</td>
        <td>: <?= $peserta->jenis_kelamin ?></td>
    </tr>
    <tr>
        <td>Kampus</td>
        <td>: <?= MyHelper::getKampus($peserta->kampus) ?></td>
        <td>Fakultas</td>
        <td>: <?= $peserta->fakultas ?></td>
    </tr>
    <tr>
        <td>Prodi</td>
        <td>: <?= $peserta->prodi ?></td>
        <td>Status Warga</td>
        <td>: <?= $peserta->status_warga ?></td>
    </tr>
    <tr>
        <td>Negara Asal</td>
        <td>: <?= $peserta->warga_negara ?></td>
        <td>Jumlah Rombongan</td>
        <td>: <?= $peserta->jumlah_rombongan ?></td>
    </tr>
    <tr>
        <td>Telp</td>
        <td>: <?= $peserta->no_telp ?></td>
        <td>Alamat Email</td>
        <td>: <?= MyHelper::getEmailUser($peserta->nim) ?></td>
    </tr>
    <tr>
        <td>Nama Ayah</td>
        <td>: <?= $peserta->nama_ayah ?></td>
        <td>Nama Ibu</td>
        <td>: <?= $peserta->nama_ibu ?></td>
    </tr>
    <tr>
        <td>Pekerjaan Ayah</td>
        <td>: <?= $peserta->pekerjaan_ayah ?></td>
        <td>Pekerjaan Ibu</td>
        <td>: <?= $peserta->pekerjaan_ibu ?></td>
    </tr>
    <tr>
        <td>Ukuran Kaos</td>
        <td>: <?= $peserta->ukuran_kaos ?></td>
        <td>Verifikator</td>
        <td>: <?= $peserta->approved_by == '' ? 'Tidak ada' : $peserta->approvedBy->nama ?></td>
    </tr>

    <tr>
        <td>Alamat</td>
        <td colspan="3">: <?= $peserta->alamat ?></td>
    </tr>

</table>

<br><br>

<table border="1" width="100%" style="padding: 4px;">
    <tr style="text-align: center;">
        <td width="8%">No</td>
        <td width="70%">Persyaratan</td>
        <td width="22%">List</td>
    </tr>
    <?php
    $no = 1;
    foreach ($persyaratans as $persyaratan) :
    ?>
        <tr>
            <td style="text-align: center;"><?= $no ?></td>
            <td><?= $persyaratan->nama ?></td>
            <td style="text-align: center;"><?= MyHelper::getStatusSyarat($peserta->id, $persyaratan->id) ?></td>
        </tr>
    <?php
        $no++;
    endforeach; ?>
</table>
<br><br><br>

<table width="100%" style="padding: 4px;">
    <tr>
        <td>O = Ada <br>X = Tidak ada</td>
    </tr>

    <?php if ($peserta->approved_by != "") :?>
    <tr><td></td></tr>
    <tr>
        <td width="65%"></td>
        <td width="35%">Ponorogo, <?= $peserta->approved_by == "" ? "-" : MyHelper::convertTanggalIndo($peserta->approved_at ? $peserta->approved_at : date('Y-m-d')) ?></td>
    </tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr><td></td></tr>
    <tr>
        <td></td>
        <td><u><?= $peserta->approved_by == "" ? "-" : $peserta->approvedBy->nama ?></u> <br>Verifikator</td>
    </tr>
    <?php endif; ?>
</table>