<?php

namespace zabachok\hydra\widgets;

use Yii;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class FileInput extends InputWidget
{
    /**
     * @var string button tag
     */
    public $buttonTag = 'div';
    /**
     * @var string button name
     */
    public $buttonName = 'Выбрать';
    /**
     * @var array button html options
     */
    public $buttonOptions = ['class' => 'btn btn-default btn-sm'];
    /**
     * @var string reset button tag
     */
    public $resetButtonTag = 'div';
    /**
     * @var string reset button name
     */
    public $resetButtonName = '<span class="text-danger glyphicon glyphicon-remove"></span> ';
    /**
     * @var array reset button html options
     */
    public $resetButtonOptions = ['class' => 'btn btn-default btn-sm'];
    /**
     * @var string widget template
     */
    public $template = '<div class="input-group">{input}<span class="btn-group">{button}{reset-button}</span></div>';


    public function run()
    {
        if ($this->hasModel())
        {
            $replace['{input}'] = Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else
        {
            $replace['{input}'] = Html::hiddenInput($this->name, $this->value, $this->options);
        }
        $replace['{button}'] = Html::tag($this->buttonTag, $this->buttonName, $this->buttonOptions);
        if(empty($this->value)) $this->resetButtonOptions['class'] .= ' hidden';
        $replace['{reset-button}'] = Html::tag($this->resetButtonTag, $this->resetButtonName,
            $this->resetButtonOptions);

        return strtr($this->template, $replace);
    }

}