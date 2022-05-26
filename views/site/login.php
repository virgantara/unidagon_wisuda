<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';

//$img= Url::to("frontend/web/Images/logo_kamp.png");
// $BaseUrl=Yii::$app->urlManager->baseUrl."/Images/logo_kamp.png";

//$img= Url::to(Yii::getAlias('@frontend')."/web/Images/logo_kamp.png");
//Yii::setAlias('@logo_unida',$img);
// $img = Yii::$app->params['front'].'/Images/logo_kamp.png';
//print_r(Yii::getAlias('@logo_unida'));exit;
?>


<div class="site-login">
    <div class="row col-lg-4" >&nbsp;</div>
    <div class="row col-lg-4" style="text-align:center">
        
        <div class="col-lg-12" >
            
<!--        <p>Please fill out the following fields to login:</p>-->
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Username', 'style' => 'height:40px;']) ?>

                <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password', 'style' => 'height:40px;']) ?>

                 <div class="form-group pull-right" style='padding-right:91px;'>
                    <p>
                    <?= Html::submitButton("Login", ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </p>
                    
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="row col-lg-4" >&nbsp;</div>
   
</div>
