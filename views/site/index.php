<?php
use app\helpers\MyHelper;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Beranda';

?>
<p>
<?php 
if(empty($periode)){
?>
<div class="alert alert-danger">
    Oops, Mohon maaf. Saat ini belum ada pembukaan pendaftaran wisuda.
</div>
<?php }
else{

    setlocale(LC_ALL, 'id_ID', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');


    $tgl_awal = strftime('%A, %d %B %Y',strtotime($periode->tanggal_buka));
    $tgl_akhir = strftime('%A, %d %B %Y',strtotime($periode->tanggal_tutup));
    echo "<h1>Pembukaan Pendaftaran ".$periode->nama_periode."</h1>";
    echo "<div class='alert alert-info'>";
    echo "Periode Pendaftaran ".$periode->nama_periode." dibuka pada ";
    echo "<b>".$tgl_awal.' s/d '.$tgl_akhir."</b>";
    echo "</div>";
}
 ?>
</p>
<p>
<?php 
if(empty($setting)){
?>
<div class="alert alert-danger">
    Oops, Mohon maaf. Saat ini belum ada informasi tentang wisuda.
</div>
<?php }
else{

    // echo "<h1 class='text-center'>".$setting->kode_setting."</h1>";
    echo "<div class='well'>";
    
    echo $setting->konten;
    echo "</div>";
}
 ?>
</p>
<?php

$this->registerJs('
', \yii\web\View::POS_READY);

?>
