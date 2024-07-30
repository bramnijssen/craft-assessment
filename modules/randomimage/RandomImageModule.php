<?php

namespace modules\randomimage;

use Craft;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterTemplateRootsEvent;
use craft\services\Dashboard;
use craft\web\View;
use modules\randomimage\widgets\RandomImageWidget;
use yii\base\Event;
use yii\base\Module as BaseModule;

/**
 * RandomImageModule module
 *
 * @method static RandomImageModule getInstance()
 */
class RandomImageModule extends BaseModule
{
    public function init(): void
    {
        Craft::setAlias('@modules\randomimage', __DIR__);

        // Set the controllerNamespace based on whether this is a console or web request
        if (Craft::$app->request->isConsoleRequest) {
            $this->controllerNamespace = 'modules\\randomimage\\console\\controllers';
        } else {
            $this->controllerNamespace = 'modules\\randomimage\\controllers';
        }

        parent::init();

        $this->attachEventHandlers();
    }

    private function attachEventHandlers(): void
    {
        Event::on(
            View::class,
            View::EVENT_REGISTER_CP_TEMPLATE_ROOTS,
            function (RegisterTemplateRootsEvent $event) {
                $event->roots[$this->id] = __DIR__ . '/templates';
            }
        );

        Event::on(
            Dashboard::class,
            Dashboard::EVENT_REGISTER_WIDGET_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = RandomImageWidget::class;
            }
        );
    }
}
