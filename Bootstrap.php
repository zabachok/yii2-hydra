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
                '<file:' . $parts['path'] . '.*>' => 'hydra/common/make',
            ],
            false
        );
//        $locator = new ServiceLocator;
//        $app->set('hydra', 'zabachok\hydra\HydraComponent');
//        // Add module I18N category.
//        if (!isset($app->i18n->translations['users']) && !isset($app->i18n->translations['users*'])) {
//            $app->i18n->translations['users'] = [
//                'class' => 'yii\i18n\PhpMessageSource',
//                'basePath' => '@vova07/users/messages',
//                'forceTranslation' => true
//            ];
//        }
    }
}