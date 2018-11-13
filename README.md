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
composer require kbrabrand/tinymce

# tell Craft to install the plugin
./craft install/plugin tinymce
```

## Configuration

### HTML Purifier Configs

You can create custom HTML Purifier configs that will be available to your CKEditor fields. They should be created as JSON files in your `config/htmlpurifier/` folder.

See the [HTML Purifier documentation] for a list of available config options. 

## Roadmap

You can track our progress toward the 1.0 GA release from the [1.0 project](https://github.com/craftcms/ckeditor/projects/1).

[TinyMCE]: https://www.tiny.cloud/
[HTML Purifier documentation]: http://htmlpurifier.org/live/configdoc/plain.html
