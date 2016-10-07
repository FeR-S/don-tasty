<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap-theme.css',
        'css/font-awesome.min.css',
        'css/main.css',
    ];
    public $js = [
        'js/headroom.min.js',
        'js/html5shiv.js',
        'js/jQuery.headroom.min.js',
        'js/respond.min.js',
        'js/template.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
