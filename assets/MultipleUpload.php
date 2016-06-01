<?php

namespace zabachok\hydra\assets;

use yii\web\AssetBundle;

class MultipleUpload extends AssetBundle
{
    public $sourcePath = '@zabachok/hydra/assets/';
    public $js = [
        'js/jquery.uploadfile.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}