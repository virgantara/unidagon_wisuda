<?php
use app\helpers\MyHelper;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Beranda';

?>

<?php

$this->registerJs('
 var introguide = introJs();
    introguide.setOptions({
        exitOnOverlayClick: false
    });
    // introguide.start();
    // // localStorage.clear();
    var doneTour = localStorage.getItem(\'evt_pa\') === \'Completed\';
    
    if(!doneTour) {
        introguide.start()

        introguide.oncomplete(function () {
            localStorage.setItem(\'evt_pa\', \'Completed\');
            Swal.fire({
              title: \'Ulangi Langkah Fitur ini ?\',
              text: "",
              icon: \'warning\',
              showCancelButton: true,
              width:\'35%\',
              confirmButtonColor: \'#3085d6\',
              cancelButtonColor: \'#d33\',
              confirmButtonText: \'Ya, ulangi lagi!\',
              cancelButtonText: \'Tidak, sudah cukup\'
            }).then((result) => {
              if (result.value) {
                introguide.start();
                localStorage.removeItem(\'evt_pa\');
              }

            });
        });

       
    }
', \yii\web\View::POS_READY);

?>
