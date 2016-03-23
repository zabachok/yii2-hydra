<?php
namespace zabachok\hydra\controllers;

use Yii;
use yii\web\Controller;
use zabachok\hydra\models\File;

class ApiController extends Controller
{
    public function actionLs($path)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $return = [
            'path'        => $path,
            'folders'     => [],
            'files'       => [],
            'breadcrumbs' => Yii::$app->hydra->getBreadcrumbs($path),
        ];

        foreach (Yii::$app->hydra->ls($path) as $item)
        {
            $data = [
                'filename'  => $item['filename'],
                'name'      => $item['name'],
                'url'       => $item['url'],
            ];
            if($item['is_dir'])
            {

            }else{
                $data['extension'] = $item['is_dir'] ? '' : $item['extension'];
                $model = new File();
                $model->attributes = $item;

                $data['view'] = $this->renderPartial('/facade/_file', ['model'=>$model]);
            }
            $type = $item['is_dir'] ? 'folders' : 'files';
            $return[$type][] = $data;
        }

        return $return;
    }
}