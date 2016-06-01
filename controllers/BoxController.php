<?php

namespace zabachok\hydra\controllers;

use Yii;
use yii\imagine\Image;
use Imagine\Image\Point;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class BoxController extends \yii\web\Controller
{
    public function actionIndex()
    {
//        echo '1111';
        echo Yii::$app->hydra->getCacheUrl('/news/1.jpg', '100x100p');
//        Yii::$app->hydra->parseCachePath('/data/cache/path/anydir/file-300x200p.jpg');
        die();
        $imagine = Image::getImagine();
        $image = $imagine->open('https://pp.vk.me/c625231/v625231488/6207f/ZdXVkWDteFk.jpg');
//        $image = $image->thumbnail(new Box(300, 100), ImageInterface::THUMBNAIL_OUTBOUND);
        $image = $image->thumbnail(new \Imagine\Image\Box(300, 100), ImageInterface::THUMBNAIL_INSET);
//        $image = $image->crop(new Point(0, 0), new Box(300, 200));
//        Yii::$app->response->headers->add('Content-Type', 'image/jpeg');
        $image->show('png');
//        Yii::$app->response->send();
        Yii::$app->end();
    }
}