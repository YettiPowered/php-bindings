# Yetti API PHP bindings

[![Build Status](https://secure.travis-ci.org/YettiPowered/php-bindings.png?branch=master)](http://travis-ci.org/YettiPowered/php-bindings)

This package contains PHP API bindings for the [Yetti API](/YettiPowered/api-docs).

For more details on Yetti, please refer to http://yetti.co.uk. We welcome comments, feedback and bug reports at support@yetti.co.uk.

## Requirements

* PHP 5.3 or above.
* PHP's cURL module.

## Examples

Please see the included docs and tests directories for help using these bindings.

## Docs

The included documentation was generated directly from the source code using the [phpDocumentor 2](http://www.phpdoc.org) tool.

To regenerate the API docs, make sure that you have phpDocumentor 2 installed, cd to the api bindings directory then run:

rm -rf docs && phpdoc

## Tests

The tests are written using [PHPUnit 3.6.11](/sebastianbergmann/phpunit/).

To run the tests, cd to the api bindings directory then run:

phpunit .

## Contributing

The Yetti APIs are under active development, as are these PHP bindings. If you find a bug or have specific comments, please use GitHub issues.
If you'd like to help us make these bindings better then feel free to fork and send pull requests.
