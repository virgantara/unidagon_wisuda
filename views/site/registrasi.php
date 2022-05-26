<?php
use app\helpers\MyHelper;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Beranda';

?>
<h1 class="sr-only">Klorofil - Free Bootstrap dashboard</h1>
                        <div class="logo text-center"><img src="<?=Yii::getAlias('@klorofil');?>/assets/img/logo_kamp.png" alt="Klorofil Logo" width="30%"></div>
                        <div class="user text-center">
                            <!-- <img src="assets/img/user-medium.png" class="img-circle" alt="Avatar"> -->
                            <h2 class="name">Graduation Registration</h2>
                        </div>
                        <form action="index.html">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Enter your NIM ...">
                                <span class="input-group-btn"><button type="submit" class="btn btn-primary"><i class="fa fa-arrow-right"></i></button></span>
                            </div>
                        </form>
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
