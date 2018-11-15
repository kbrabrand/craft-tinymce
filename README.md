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
You can creaete custom TinyMCE configs that will be available to your TinyMCE fields. They should be created as JSON files in your `config/tinymce/` folder. The options are the ones you find in the [TinyMCE documentation] – with two exceptions: the theme will be set to modern, and the selector to the id of the field rendered by Craft.

```json
{
    "menubar": false,
    "plugins": ["autoresize", "link", "code", "table contextmenu paste help"],
    "toolbar": "bold italic | alignleft aligncenter alignright alignjustify | removeformat | table",
    "table_class_list": [
        {"title": "None", "value": ""},
        {"title": "Dog", "value": "dog"},
        {"title": "Cat", "value": "cat"}
    ]
}
```

### HTML Purifier Configs

You can create custom HTML Purifier configs that will be available to your TinyMCE fields. They should be created as JSON files in your `config/htmlpurifier/` folder.

See the [HTML Purifier documentation] for a list of available config options. 

[TinyMCE documentation]: https://www.tiny.cloud/docs/general-configuration-guide/
[TinyMCE]: https://www.tiny.cloud/
[HTML Purifier documentation]: http://htmlpurifier.org/live/configdoc/plain.html
