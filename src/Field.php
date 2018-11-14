<?php

namespace kbrabrand\tinymce;

use Craft;
use craft\base\ElementInterface;
use craft\helpers\FileHelper;
use craft\helpers\HtmlPurifier;
use craft\helpers\Json;
use craft\helpers\StringHelper;
use craft\helpers\Template;
use kbrabrand\tinymce\assets\field\FieldAsset;
use kbrabrand\tinymce\assets\tinymce\TinymceAsset;;
use yii\db\Schema;

/**
 * TinyMCE field type.
 *
 * @author Kristoffer Brabrand <kristoffer@brabrand.no>
 * @since 1.0
 */
class Field extends \craft\base\Field
{
    // Static
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('tinymce', 'TinyMCE');
    }

    // Properties
    // =========================================================================

    /**
     * @var string|null The HTML Purifier config file to use
     */
    public $purifierConfig;

    /**
     * @var string|null The TinyMCE config file to use
     */
    public $tinymceConfig;

    /**
     * @var bool Whether the HTML should be purified on save
     */
    public $purifyHtml = true;

    /**
     * @var string The type of database column the field should have in the content table
     */
    public $columnType = Schema::TYPE_TEXT;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('tinymce/_field_settings', [
            'field' => $this,
            'purifierConfigOptions' => $this->_getCustomConfigOptions('htmlpurifier'),
            'tinymceConfigOptions' => $this->_getCustomConfigOptions('tinymce'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return $this->columnType;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if ($value === null || $value instanceof \Twig_Markup) {
            return $value;
        }

        // TODO: See if this is still necessary after updating to latest TinyMCE.
        if ($value === '<p>&nbsp;</p>') {
            return null;
        }

        // Prevent everyone from having to use the |raw filter when outputting RTE content
        return Template::raw($value);
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        /** @var \Twig_Markup|null $value */
        if (!$value) {
            return null;
        }

        // Get the raw value
        $value = (string)$value;

        if (!$value) {
            return null;
        }

        if ($this->purifyHtml) {
            $value = HtmlPurifier::process($value, $this->_getPurifierConfig());
        }

        if (Craft::$app->getDb()->getIsMysql()) {
            // Encode any 4-byte UTF-8 characters.
            $value = StringHelper::encodeMb4($value);
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function isValueEmpty($value, ElementInterface $element): bool
    {
        /** @var \Twig_Markup|null $value */
        return $value === null || parent::isValueEmpty((string)$value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        $view = Craft::$app->getView();
        $view->registerAssetBundle(FieldAsset::class);

        $id = $view->formatInputId($this->handle);
        $nsId = $view->namespaceInputId($id);
        $encValue = htmlentities((string)$value, ENT_NOQUOTES, 'UTF-8');
        $themeUrl = \Craft::$app->assetManager->getPublishedUrl(TinymceAsset::getSourcePath() . '/themes/modern/theme.min.js', true);

        $config = $this->_getTinymceConfig();
        $theme = array_key_exists('theme', $config) ? 'theme: "' . $config['theme'] . '",' : '';
        $plugins = array_key_exists('plugins', $config) ? 'plugins: ' . json_encode($config['plugins']) . ',' : '';
        $toolbar = array_key_exists('toolbar', $config) ? 'toolbar: "' . implode(' | ', $config['toolbar']) . '",' : '';
        $menubar = array_key_exists('menubar', $config) ? ((bool) $config['menubar'] ? 'true' : 'false') : 'true';

        $js = <<<JS
tinymce.init({
  selector: '#{$nsId}',
  height: 500,
  menubar: {$menubar},
  {$plugins}
  {$toolbar}
  theme_url: '{$themeUrl}'
});
JS;
        
        $view->registerJs($js);

        return "<textarea id='{$id}' name='{$this->handle}'>{$encValue}</textarea>";
    }

    /**
     * @inheritdoc
     */
    public function getStaticHtml($value, ElementInterface $element): string
    {
        /** @var \Twig_Markup|null $value */
        return '<div class="text">'.($value ?: '&nbsp;').'</div>';
    }

    // Private Methods
    // =========================================================================

    /**
     * Returns the available Redactor config options.
     *
     * @param string $dir The directory name within the config/ folder to look for config files
     * @return array
     */
    private function _getCustomConfigOptions(string $dir): array
    {
        $options = ['' => Craft::t('app', 'Default')];
        $path = Craft::$app->getPath()->getConfigPath().DIRECTORY_SEPARATOR.$dir;

        if (is_dir($path)) {
            $files = FileHelper::findFiles($path, [
                'only' => ['*.json'],
                'recursive' => false
            ]);

            foreach ($files as $file) {
                $options[pathinfo($file, PATHINFO_BASENAME)] = pathinfo($file, PATHINFO_FILENAME);
            }
        }

        return $options;
    }

    /**
     * Returns the HTML Purifier config used by this field.
     *
     * @return array
     */
    private function _getPurifierConfig(): array
    {
        if ($config = $this->_getConfig('htmlpurifier', $this->purifierConfig)) {
            return $config;
        }

        // Default config
        return [
            'Attr.AllowedFrameTargets' => ['_blank'],
            'HTML.AllowedComments' => ['pagebreak'],
        ];
    }

    /**
     * Returns the HTML Purifier config used by this field.
     *
     * @return array
     */
    private function _getTinymceConfig(): array
    {
        if ($config = $this->_getConfig('tinymce', $this->tinymceConfig)) {
            return $config;
        }

        // Default config
        return [];
    }

    /**
     * Returns a JSON-decoded config, if it exists.
     *
     * @param string $dir The directory name within the config/ folder to look for the config file
     * @param string|null $file The filename to load
     * @return array|false The config, or false if the file doesn't exist
     */
    private function _getConfig(string $dir, string $file = null)
    {
        if (!$file) {
            return false;
        }

        $path = Craft::$app->getPath()->getConfigPath().DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$file;

        if (!is_file($path)) {
            return false;
        }

        return Json::decode(file_get_contents($path));
    }
}
