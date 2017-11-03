# Laravel Hashid

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elfsundae/laravel-hashid.svg?style=flat-square)](https://packagist.org/packages/elfsundae/laravel-hashid)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/ElfSundae/laravel-hashid/master.svg?style=flat-square)](https://travis-ci.org/ElfSundae/laravel-hashid)
[![StyleCI](https://styleci.io/repos/106044131/shield)](https://styleci.io/repos/106044131)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/8373554a-0842-488a-818a-b90badef6a58.svg?style=flat-square)](https://insight.sensiolabs.com/projects/8373554a-0842-488a-818a-b90badef6a58)
[![Quality Score](https://img.shields.io/scrutinizer/g/ElfSundae/laravel-hashid.svg?style=flat-square)](https://scrutinizer-ci.com/g/ElfSundae/laravel-hashid)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/ElfSundae/laravel-hashid/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/ElfSundae/laravel-hashid/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/elfsundae/laravel-hashid.svg?style=flat-square)](https://packagist.org/packages/elfsundae/laravel-hashid)

Laravel Hashid provides a unified API across various drivers such as [Base62], [Base64], [Hashids] and [Optimus], with support for multiple connections or different encoding options. It offers a convenient way to obfuscate your data by generating reversible, non-sequential, URL-safe identifiers.

<!-- MarkdownTOC -->

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Built-in Drivers](#built-in-drivers)
- [Extending](#extending)
    - [Adding Custom Drivers](#adding-custom-drivers)
    - [Extend Existing Connections Or Drivers](#extend-existing-connections-or-drivers)
- [Testing](#testing)
- [License](#license)

<!-- /MarkdownTOC -->

## Installation

You can install this package using the [Composer](https://getcomposer.org) manager:

```sh
$ composer require elfsundae/laravel-hashid
```

For Lumen or earlier Laravel than v5.5, you need to register the service provider manually:

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

Our well documented configuration file is extremely similar to the configurations of numerous Laravel manager integrations such as Database, Queue, Cache and Filesystem. So you do not need to spend extra time to learn how to configure Hashid.

Additionally, for simplicity you do not need to add singleton drivers like Base64 to your config file as they have no encoding options, unless you would like to specify a meaningful connection name.

Let's see an example of the configuration:

```php
'default' => 'id',

'connections' => [

    'basic' => [
        'driver' => 'base64',
    ],

    'id' => [
        'driver' => 'hashids_integer',
        'salt' => 'My Application',
        'min_length' => 6,
        'alphabet' => '1234567890abcdef',
    ],

    'hashids' => [
        'driver' => 'hashids',
        'salt' => 'sweet girl',
    ],

    'base62' => [
        'driver' => 'base62',
        'characters' => 'f9FkqDbzmn0QRru7PBVeGl5pU28LgIvYwSydK41sCO3htaicjZoWAJNxH6EMTX',
    ],

],
```

## Usage

The `hashid()` helper or the `Hashid` facade may be used to interact with any of your configured connections or drivers.

```php
use ElfSundae\Laravel\Hashid\Facades\Hashid;

// Obtain the default connection instance
hashid();
Hashid::connection();

// Obtain the "base62" connection instance
hashid('base62');
Hashid::connection('base62');

// Obtain the Base64 driver instance
hashid('base64');
Hashid::connection('base64');
```

There are only two methods you need to know to use any connection or driver:

 - `encode()` for encoding data.
 - `decode()` for decoding data.

```php
hashid()->encode(123456);

hashid('base64')->decode('TGFyYXZlbA');

Hashid::encode(123456);

Hashid::connection('hashids')->decode('xkNDJ');
```

And there are also two corresponding helper functions:

- `hashid_encode($data, $name = null)`
- `hashid_decode($data, $name = null)`

```php
hashid_encode(123456);

hashid_decode('TGFyYXZlbA', 'base64');
```

## Built-in Drivers


## Extending

### Adding Custom Drivers

### Extend Existing Connections Or Drivers

## Testing

```sh
$ composer test
```

## License

This package is open-sourced software licensed under the [MIT License](LICENSE.md).

[base62]: https://github.com/tuupola/base62
[base64]: https://github.com/ElfSundae/urlsafe-base64
[hashids]: http://hashids.org/php/
[optimus]: https://github.com/jenssegers/optimus
