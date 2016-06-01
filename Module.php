<?php

namespace zabachok\hydra;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'zabachok\hydra\controllers';
    public $defaultController = 'facade';

    public function init()
    {
//        \Yii::setAlias('blog', dirname(__DIR__) .'/blog');
        parent::init();
        
        // custom initialization code goes here
    }
}
