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
        $extension = Yii::$app->hydra->getExtension($file);

        $image = Yii::$app->hydra->generateCacheimage($file);
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', Yii::$app->hydra->imagesContentType[$extension]);
        return $image;
    }
}