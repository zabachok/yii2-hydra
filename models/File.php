<?php
namespace zabachok\hydra\models;

use Yii;
use yii\base\Model;

class File extends Model
{
    public $filename;
    public $name;
    public $extension;
    public $fullpath;
    public $url;
    public $filespath;

    public function rules()
    {
        return [
            [['filename', 'extension', 'fullpath', 'name', 'url', 'filespath'], 'string']
        ];
    }

    public function getPreview()
    {
        if(Yii::$app->hydra->isImage($this->extension))
        {

            return Yii::$app->hydra->getCacheUrl($this->filespath, 'preview');
        }
    }
}