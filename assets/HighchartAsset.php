<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use Yii;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Nenad Zivkovic <nenad@freetuts.org>
 * 
 * @since 2.0
 */
class HighchartAsset extends AssetBundle
{
	public $sourcePath = '@npm/highcharts/';
    // public $baseUrl = '@themes';

    public $css = [
    ];

    public $js = [
    	'highcharts.js',
        'highcharts-more.js',
        'modules/solid-gauge.js',
        'modules/bullet.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',

    ];

}