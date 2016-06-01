<?php

namespace zabachok\hydra\widgets;

use yii\widgets\InputWidget;
use yii\helpers\Html;

class MDEditor extends InputWidget
{

    public $options = [
        'class'=>'form-control',
    ];



    public function run()
    {
        if ($this->hasModel())
        {
            $input = Html::activeTextarea($this->model, $this->attribute, $this->options);
//            $attributeId =  Html::getInputId($this->model, $this->attribute);
//            $id =  Html::getInputId($this->model, 'hydra_' . $this->attribute);
//            $name =  Html::getInputName($this->model, 'hydra_' . $this->attribute);
//            $this->value = $this->model{$this->attribute};
        } else
        {
            $input = Html::textarea($this->name, $this->value, $this->options);
//            $name = $id = 'hydra_' . $this->name;
//            $attributeId = $this->name;
        }
        print_r($this->options);
        return $this->render('mdeditor', [
            'input' => $input,
        ]);
    }

}