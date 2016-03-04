# InlineSvg

PHP library to embed svg icons in the html pages so you can use css to change the style. Why do not use icon fonts? [Here a comparison svg vs fonts](http://css-tricks.com/icon-fonts-vs-svg/)

## Requirements:

* PHP 5.4+
* SimpleXMLElement (extension enabled by default)

## Usage:

```php
//Include the autoload if you don't use composer
include 'icon-embedder/src/autoloader.php'

use InlineSvg\IconSet;
use InlineSvg\Sources\FileSystem;

//Create a file system source pointing to the directory where the svg files are stored.
$source = new FileSystem('path/to/svg/files');

//Create an Collection instance passing the source
$icons = new Collection($source);

//Insert the svg code in your html templates:
echo $icons->get('edit'); // <svg ... </svg>

//Modify any attribute
echo $icons->get('edit')->withAttribute('class', 'big-icon'); // <svg class="big-icon" .. </svg>

//Make the svg accesible
echo $icons->get('edit')->withA11y('The edit icon'); // <svg role="img" aria-labelledby="icon-edit-123-title"><title id="icon-edit-123-title">The edit icon</title> .. </svg>
```
