# TinyMCE (Beta) for Craft CMS

This plugin adds a TinyMCE field type to Craft CMS, which provides a rich text editor powered by [TinyMCE] (v4).

## Requirements

This plugin requires Craft CMS 3.0.0-RC15 or later.

## Installation

You can install this plugin with Composer.

#### With Composer

Open your terminal and run the following commands:

```bash
# go to the project directory
cd /path/to/my-project.test

# tell Composer to load the plugin
composer require kbrabrand/craft-tinymce

# tell Craft to install the plugin
./craft install/plugin tinymce
```

## Configuration

### TinyMCE config
You can creaete custom TinyMCE configs that will be available to your TinyMCE fields. They should be created as JSON files in your `config/tinymce/` folder. The structure is as follows:

```json
{
    "menubar": false,
    "plugins": [
        "link", 
        "code", 
        "table contextmenu paste help"
    ],
    "toolbar": [
        "bold italic", 
        "alignleft aligncenter alignright alignjustify", 
        "removeformat", 
        "table"
    ]
}
```

- `menubar` option can be used to turn off the menu bar
- `plugins` is an array of plugins to enable
- `toolbar` is an array of groups of buttons to show on the toolbar

### HTML Purifier Configs

You can create custom HTML Purifier configs that will be available to your TinyMCE fields. They should be created as JSON files in your `config/htmlpurifier/` folder.

See the [HTML Purifier documentation] for a list of available config options. 

[TinyMCE]: https://www.tiny.cloud/
[HTML Purifier documentation]: http://htmlpurifier.org/live/configdoc/plain.html
