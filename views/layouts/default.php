<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\assets\SweetalertAsset;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;
use kartik\nav\NavX;

AppAsset::register($this);
SweetalertAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!-- VENDOR CSS -->
    
    <!-- ICONS -->
    <link rel="apple-touch-icon" sizes="76x76" href="<?=Yii::getAlias('@klorofil');?>/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?=Yii::getAlias('@klorofil');?>/assets/img/favicon.png">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title.' '.Yii::$app->name) ?></title>

    <?php $this->head(); ?>
    <style type="text/css">
        .swal2-popup {
          font-size: 1.6rem !important;
        }
        .hero-mini {
  background-image: linear-gradient(to right, #697ded, #5e30c1);
  padding-top: 40px;
  height: 500px;
  display: flex;
  align-items: center;
  color: #fff;
    </style>
</head>
<body>
<?php $this->beginBody() ?>
    
<?=$content?>


<!--End mc_embed_signup-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
