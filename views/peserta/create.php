<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Peserta */

$this->title = 'Create Peserta';
$this->params['breadcrumbs'][] = ['label' => 'Pesertas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h3><?= Html::encode($this->title) ?></h3>
<div class="row">
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body ">

                <div class="alert alert-info">
                    <h3><i class="fa fa-warning"></i> Perhatian</h3>
                    Jika ada kesalahan pada data Anda, silakan mengupdate data dari SIAKAD.
                </div>
                
                <?php

                switch ($step) {
                    case 1:
                        echo $this->render('_form', [
                            'model' => $model,
                        ]);
                        break;
                    case 2:
                        echo $this->render('_form2', [
                            'model' => $model,
                        ]);
                        break;
                    case 3:
                        echo $this->render('_form3', [
                            'model' => $model,
                            'list_syarat' => $list_syarat,
                            'list_bukti_peserta' => $list_bukti_peserta
                        ]);
                        break;
                    case 4:
                        echo $this->render('_form4', [
                            'model' => $model,
                        ]);
                        break;
                    case 5:
                        echo $this->render('_form5', [
                            'model' => $model,
                            'list_syarat' => $list_syarat,
                            'list_bukti_peserta' => $list_bukti_peserta
                        ]);
                        break;
                }


                ?>
            </div>
        </div>
    </div>
</div>