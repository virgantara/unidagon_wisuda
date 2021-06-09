<?php

namespace app\assets;

use yii\web\AssetBundle;
use Yii;

class EventAsset extends AssetBundle
{
    public $basePath = '@npm/fullcalendar';
    // public $baseUrl = '@themes';

    public $css = [
        'main.min.css',
    ];

    public $js = [
    	// 'js/jquery.ui.touch-punch.min.js',
     //    'js/moment.min.js',
     //    'js/fullcalendar.min.js',
        'main.min.js'

    ];

    public $depends = [
        'yii\web\YiiAsset',

    ];
}
