<?php

namespace zabachok\hydra\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class FileInput extends InputWidget
{

    public $resolution;
    public $resetButton = [
        'tag'     => 'div',
        'label'   => '<span class="text-danger fa fa-remove"></span>',
        'options' => ['class' => 'btn btn-default btn-sm'],
    ];
    /**
     * @var string widget template
     */
    public $template = '<div class="input-group">{input}<span class="btn-group">{preview}{reset-button}</span></div>';

    public $fileInput = [
        'name'    => null,
        'options' => [],
    ];

    public function run()
    {
        if ($this->hasModel())
        {
            $replace['{input}'] = Html::activeHiddenInput($this->model, $this->attribute, $this->options);
            $attributeId =  Html::getInputId($this->model, $this->attribute);
            $id =  Html::getInputId($this->model, 'hydra_' . $this->attribute);
            $name =  Html::getInputName($this->model, 'hydra_' . $this->attribute);
            $this->value = $this->model{$this->attribute};
        } else
        {
            $replace['{input}'] = Html::hiddenInput($this->name, $this->value, $this->options);
            $name = $id = 'hydra_' . $this->name;
            $attributeId = $this->name;
        }

        $fileInputName = empty($this->fileInputName) ? $name : $this->fileInputName;

            $replace['{preview}'] = 'Файл еще не загружен';

        if (!empty($this->value))
        {
            $replace['{preview}'] = $this->value;
            $extension = Yii::$app->hydra->getExtension($this->value);
            if(Yii::$app->hydra->isImage($extension))
            {
                $replace['{preview}'] = Html::img(Yii::$app->hydra->getCacheUrl($this->value, $this->resolution), ['id'=>$id . '_image']);
            }

            $replace['{reset-button}'] = Html::tag($this->resetButton['tag'], $this->resetButton['label'],
                ArrayHelper::merge(['onClick' => '$("#' . $attributeId . '").val("");$("#' . $id . '_image' . '").remove();$(this).remove();'], $this->resetButton['options']));
        }

        $replace['{input}'] .= Html::fileInput($fileInputName, null,
            ArrayHelper::merge(['id' => $id], $this->fileInput['options']));

        return strtr($this->template, $replace);
    }

}