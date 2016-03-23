<?php
namespace zabachok\hydra\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class CommonController extends Controller
{
    public function actionMake($file)
    {
        $file = '/' . $file;
//        die($file);
        $extension = Yii::$app->hydra->getExtension($file);
        Yii::$app->response->headers->add('Content-Type', Yii::$app->hydra->imagesContentType[$extension]);
        Yii::$app->response->format = Response::FORMAT_RAW;

        return Yii::$app->hydra->generateCacheimage($file);
    }
}