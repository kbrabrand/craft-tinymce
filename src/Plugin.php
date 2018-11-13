<?php

namespace kbrabrand\tinymce;

use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use yii\base\Event;

/**
 * TinyMCE plugin.
 *
 * @author Kristoffer Brabrand <kristoffer@brabrand.no>
 * @since 1.0
 */
class Plugin extends \craft\base\Plugin
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Event::on(Fields::class, Fields::EVENT_REGISTER_FIELD_TYPES, function(RegisterComponentTypesEvent $e) {
            $e->types[] = Field::class;
        });
    }
}
