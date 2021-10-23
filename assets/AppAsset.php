<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/css/bootstrap.min.css',
        'assets/css/app.min.css',
        /*'assets/css/app-dark.min.css',
        'assets/css/app-rtl.min.css',
        'assets/css/bootstrap-dark.min.css',
        'assets/css/bootstrap-rtl.min.css',
        'assets/css/pages/tab-page.css',*/
        'assets/css/icons.min.css',
        //'assets/css/icons-rtl.min.css',
    ];
    public $js = [
        'assets/js/vendor.min.js',
        'assets/js/app.min.js',
        'js/jquery.sortable.js',
    ];
    public $depends = [
        /* 'yii\web\YiiAsset',
         'yii\bootstrap\BootstrapAsset',*/
    ];
}
