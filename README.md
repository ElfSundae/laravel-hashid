# Laravel Hashid

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elfsundae/laravel-hashid.svg?style=flat-square)](https://packagist.org/packages/elfsundae/laravel-hashid)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/ElfSundae/laravel-hashid/master.svg?style=flat-square)](https://travis-ci.org/ElfSundae/laravel-hashid)
[![StyleCI](https://styleci.io/repos/106044131/shield)](https://styleci.io/repos/106044131)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/8373554a-0842-488a-818a-b90badef6a58.svg?style=flat-square)](https://insight.sensiolabs.com/projects/8373554a-0842-488a-818a-b90badef6a58)
[![Quality Score](https://img.shields.io/scrutinizer/g/ElfSundae/laravel-hashid.svg?style=flat-square)](https://scrutinizer-ci.com/g/ElfSundae/laravel-hashid)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/ElfSundae/laravel-hashid/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/ElfSundae/laravel-hashid/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/elfsundae/laravel-hashid.svg?style=flat-square)](https://packagist.org/packages/elfsundae/laravel-hashid)

Laravel Hashid provides a convenient, easy to use, unified API to obfuscate your data by generating reversible, URL safe identifiers for numbers, strings or bytes. It supports multiple connections dealing with different drivers or encoding options.

## Installation

You can install this package using the [Composer](https://getcomposer.org) manager:

```sh
$ composer require elfsundae/laravel-hashid
```

For Laravel before v5.5 or Lumen, you need to register the service manually:

```php
ElfSundae\Laravel\Hashid\HashidServiceProvider::class
```

Then publish the configuration file:

```sh
# For Laravel application:
$ php artisan vendor:publish --tag=hashid

# For Lumen application:
$ cp vendor/elfsundae/laravel-hashid/config/hashid.php config/hashid.php
```

## Configuration

## Hashid Usage

## Extending

### Adding Custom Drivers

### Extend Existing Connections Or Drivers

## Testing

```sh
$ composer test
```

## License

This package is open-sourced software licensed under the [MIT License](LICENSE.md).
