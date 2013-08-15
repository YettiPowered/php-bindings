# Yetti API PHP bindings

[![Build Status](https://secure.travis-ci.org/YettiPowered/php-bindings.png?branch=master)](http://travis-ci.org/YettiPowered/php-bindings)

This package contains PHP API bindings for the [Yetti API](https://github.com/YettiPowered/api-docs).

For more details on Yetti, please refer to http://yetti.co.uk. We welcome comments, feedback and bug reports at support@yetti.co.uk.

## Requirements

* PHP 5.3 or above.
* PHP's cURL module.

## Installation

Install with composer, simply add to your composer.json:

```
"require": {
	"yetti/api": "dev-master"
}
```

## Examples

Creating an item is simple:

```php
use Yetti\API\Webservice as Webservice;
use Yetti\API\Item as Item;

Webservice::setDefaultBaseUri('https://yoursite.secure.yetti.co.uk');
Webservice::setDefaultAccessKey('your-access-key');
Webservice::setDefaultPrivateKey('your-private-key');

$item = new Item();
$item->loadTemplate(1);
$item->setName('my-big-news');
$item->setPropertyValue('Name', 'My big news..!');
$item->setPropertyValue('Body', 'I have decided to become a whale.');

echo $item->save()->success() ? 'Item saved' : 'Save failed';
```

And there really isn't anything much more complicated than that.
Please see the included docs and tests directories for additional help and examples.

## Docs

The included documentation was generated directly from the source code using the [phpDocumentor 2](http://www.phpdoc.org) tool.

To regenerate the API docs, make sure that you have phpDocumentor 2 installed, cd to the api bindings directory then run:

rm -rf docs && phpdoc

## Tests

The tests are written using [PHPUnit 3.7.24](/sebastianbergmann/phpunit/).

To run the tests, cd to the api bindings directory then run:

```
composer install
phpunit .
```

## Contributing

The Yetti APIs are under active development, as are these PHP bindings. If you find a bug or have specific comments, please use GitHub issues.
If you'd like to help us make these bindings better then feel free to fork and send pull requests.
