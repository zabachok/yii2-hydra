Hydra - filemanager
===================
Useful file manager for yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist zabachok/yii2-hydra "*"
```

or add

```
"zabachok/yii2-hydra": "*"
```

to the require section of your `composer.json` file.


## Config

```php
'hydra'       => [
            'class' => 'zabachok\hydra\Module',
//            'forseSave'=>false,
        ],
```