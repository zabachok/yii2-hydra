<?php

namespace zabachok\hydra\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class MultipleUpload extends Widget
{
    public static $autoIdPrefix = 'hydramfu';

    public $url;

    public $fileName;

    public $data;

    public $onSuccess;

    public $area = [
        'tag'     => 'div',
        'options' => [],
    ];

    public $hint = 'Drag & drop files';

    public $clientOptions = [];

    public function run()
    {
        \zabachok\hydra\assets\MultipleUpload::register($this->view);
        $this->view->registerJs('$("#' . $this->id . '").uploadFile(' . json_encode($this->makeConfig()) . ')');
        return Html::tag($this->area['tag'], $this->hint, ArrayHelper::merge($this->area['options'], ['id'=>$this->id]));
    }

    public function makeConfig()
    {
        $result = [
            'fileName' => $this->fileName,
            'url'      => $this->url,
        ];
        if (!empty($this->data)) $result['formData'] = $this->data;
        if (!empty($this->onSuccess)) $result['formData'] = $this->onSuccess;

        return ArrayHelper::merge($this->clientOptions, $result);
    }
}