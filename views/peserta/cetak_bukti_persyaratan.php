<?php

use app\helpers\MyHelper;

?>
<table width="100%" style="padding: 4px;">
    <tr>
        <td style="text-align: center;">
            <span style="font-size: 1.1em">Persyaratan Pendaftaran Wisuda</span>
        </td>
    </tr>
    <tr>
        <td style="text-align: center;">
            <span style="font-size: 1.1em">Universitas Darussalam Gontor</span>
        </td>
    </tr>
</table>
<br><br>
<table width="100%" style="padding: 4px;">
    <tr>
        <td width="20%">NIM</td>
        <td width="80%">: <?= $peserta->nim ?></td>
    </tr>
    <tr>
        <td>Nama Lengkap</td>
        <td>: <?= $peserta->nama_lengkap ?></td>
    </tr>
    <tr>
        <td>Program Studi</td>
        <td>: <?= $peserta->prodi ?></td>
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
    <tr>
        <td width="65%"></td>
        <td width="35%">Verifikator :</td>
    </tr>
    <tr>
        <td></td>
        <td><?= $peserta->approved_by == "" ? "-" : $peserta->approvedBy->nama ?></td>
    </tr>
</table>