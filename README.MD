# dmenu-translator
I made this tool for myself because sometimes I'm literally too lazy to even just go to Google Translator and paste some
 text. 

## Requirements
* [LibreTranslate server](https://github.com/LibreTranslate/LibreTranslate)
* PHP (only tested with PHP 8.0)
* Linux with notify-send, xclip, dmenu, and curl

## Preparation
### LibreTranslate
Make sure you've got an API-key. If you own the server instance, you can create one with the following command:
`ltmanage keys add 120`.

Refer to the [LibreTranslate documentation](https://github.com/LibreTranslate/LibreTranslate#add-new-keys) for more
 information. 

### dmenu-translator
Copy `env.php.example` to `env.php`
```
cp env.php.example env.php
```
and enter your API-key.

## Usage
The syntax is as follows:
`php index.php from to source [options]`.

So to translate text from Portuguese to English from the clipboard you would want to use:
```
php index.php pt en clipboard
```

To translate English text that you've entered into dmenu to Portuguese, you should use the following:
```
php index.php en pt input --display-result
```

The `--display-result` (or `-d`) option makes sure that the translated text will be displayed using ``notify-send``.
 For the other options, take a look in `index.php` at the switch on line 29.

## i3wm
I use i3wm bindings for quick access to a lot of useful scripts including this script. Here are my bindings for
 dmenu-translator:
```
# translate Portuguese from the clipboard into English
bindsym Ctrl+$Alt+e exec --no-startup-id "php ~/repos/dmenu-translator/index.php pt en clipboard -d"

# translate English from dmenu into Portuguese
bindsym Ctrl+$Alt+p exec --no-startup-id "php ~/repos/dmenu-translator/index.php en pt input -c"
```