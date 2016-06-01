<?php

namespace zabachok\hydra\controllers;

use Yii;
use yii\helpers\FileHelper;
use zabachok\hydra\components\UploadedFile;

class FacadeController extends \yii\web\Controller
{

    public function actionTest()
    {
        $name = 'u';
        if (Yii::$app->request->isPost) {
//            $file = UploadedFile::getInstanceByName('file');
//            $name = $file->saveAs('/dir/file.jpg');
        }
        return $this->render('test', [
            'name'=>$name,
        ]);
    }

    public function actionIndex()
    {

//        echo FileHelper::normalizePath('/web/zabachok.net/www/../bt/');
//        echo Yii::$app->hydra->filesPath;
//        print_r(Yii::$app->hydra->ls('/folder', 'files', false,['only'=>'image']));
        return $this->render('index');
    }
    
}