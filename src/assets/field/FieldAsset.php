<?php

namespace kbrabrand\tinymce\assets\field;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;
use kbrabrand\tinymce\assets\tinymce\TinymceAsset;

/**
 * TinyMCE field asset bundle
 */
class FieldAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__.'/dist';

        $this->depends = [
            CpAsset::class,
            TinymceAsset::class,
        ];

        $this->css = [
            'css/tinymce-field.min.css',
        ];

        parent::init();
    }
}
