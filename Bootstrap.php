<?php
namespace zabachok\hydra;

use yii\base\BootstrapInterface;
use yii\di\ServiceLocator;

/**
 * Users module bootstrap class.
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // Add module URL rules.
        $parts = parse_url(\Yii::$app->hydra->cacheUrl);

        $app->urlManager->addRules(
            [
                'hydra'               => 'hydra/facade/index',
                '<file:' . trim($parts['path'], '/') . '.*>' => 'hydra/common/make',
            ],
            false
        );

        if (!isset($app->i18n->translations['hydra']) && !isset($app->i18n->translations['hydra*']))
        {
            $app->i18n->translations['hydra'] = [
                'class'            => 'yii\i18n\PhpMessageSource',
                'basePath'         => '@zabachok/hydra/messages',
                'forceTranslation' => true,
            ];

        }
    }
}