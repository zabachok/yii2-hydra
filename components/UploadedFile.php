<?php
namespace zabachok\hydra\components;

class UploadedFile extends \yii\web\UploadedFile
{
    public function saveAs($filePath, $toCompress=null)
    {
        if ($this->error == UPLOAD_ERR_OK) {
            return \Yii::$app->hydra->saveFile($this->tempName, $filePath, $toCompress);
        }
        return false;
    }
}