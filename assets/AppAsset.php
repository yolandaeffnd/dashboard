<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/eofficestyle.css',
        'css/eofficestyle_bo.css',
        'adminlte/css/AdminLTE.css',
        'clockpicker/dist/bootstrap-clockpicker.min.css'//Untuk Clockpicker
    ];
    public $js = [
        'adminlte/js/app.min.js',
        'clockpicker/dist/bootstrap-clockpicker.min.js'//Untuk Clockpicker
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
