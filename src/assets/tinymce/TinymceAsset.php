<?php

namespace kbrabrand\tinymce\assets\tinymce;

use craft\web\AssetBundle;

/**
 * TinyMCE asset bundle.
 *
 * @author Kristoffer Brabrand <kristoffer@brabrand.no>
 * @since 1.0
 */
class TinymceAsset extends AssetBundle
{
    public static function getSourcePath() {
        $installedAsDep = basename(dirname(__DIR__, 5)) === 'vendor';
        
        if ($installedAsDep) {
            return dirname(__DIR__, 5) . '/tinymce/tinymce';
        }

        // Seems like it wasn't installed in another project. We'll try local vendor folder
        return dirname(__DIR__, 3).'/vendor/tinymce/tinymce';
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = self::getSourcePath();
        $this->js = ['tinymce.min.js'];
        
        parent::init();
    }
}
