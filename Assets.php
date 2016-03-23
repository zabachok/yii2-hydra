<?php
namespace zabachok\hydra;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{
    public $js = [
        'js/hydra.js',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__."/assets";
        parent::init();
    }
}