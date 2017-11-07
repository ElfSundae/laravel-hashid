# Laravel Hashid

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elfsundae/laravel-hashid.svg?style=flat-square)](https://packagist.org/packages/elfsundae/laravel-hashid)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/ElfSundae/laravel-hashid/master.svg?style=flat-square)](https://travis-ci.org/ElfSundae/laravel-hashid)
[![StyleCI](https://styleci.io/repos/106044131/shield)](https://styleci.io/repos/106044131)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/8373554a-0842-488a-818a-b90badef6a58.svg?style=flat-square)](https://insight.sensiolabs.com/projects/8373554a-0842-488a-818a-b90badef6a58)
[![Quality Score](https://img.shields.io/scrutinizer/g/ElfSundae/laravel-hashid.svg?style=flat-square)](https://scrutinizer-ci.com/g/ElfSundae/laravel-hashid)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/ElfSundae/laravel-hashid/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/ElfSundae/laravel-hashid/?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/elfsundae/laravel-hashid.svg?style=flat-square)](https://packagist.org/packages/elfsundae/laravel-hashid)

Laravel Hashid provides a unified API across various drivers such as [Base62], [Base64], [Hashids] and [Optimus], with support for multiple connections or different encoding options. It offers a simple, elegant way to obfuscate your data by generating reversible, non-sequential, URL-safe identifiers.

<!-- MarkdownTOC -->

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Built-in Drivers](#built-in-drivers)
    - [Base62](#base62)
    - [Base64](#base64)
    - [Hashids](#hashids)
    - [Hex](#hex)
    - [Optimus](#optimus)
- [Custom Drivers](#custom-drivers)
- [Donation 赞赏](#donation-%E8%B5%9E%E8%B5%8F)

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

    'hashids' => [
        'driver' => 'hashids',
        'salt' => 'sweet girl',
    ],

    'id' => [
        'driver' => 'hashids_integer',
        'salt' => 'My Application',
        'min_length' => 6,
        'alphabet' => '1234567890abcdef',
    ],

    'base62' => [
        'driver' => 'base62',
        'characters' => 'f9FkqDbzmn0QRru7PBVeGl5pU28LgIvYwSydK41sCO3htaicjZoWAJNxH6EMTX',
    ],

],
```

## Usage

The `hashid()` helper or the `Hashid` facade may be used to interact with any of your configured connections or drivers:

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
Hashid::driver('base64');
```

There are only two methods you need to know to use any connection or driver:

 - `encode($data)` for encoding data.
 - `decode($data)` for decoding data.

```php
hashid()->encode(123456);

hashid('base64')->decode('TGFyYXZlbA');

Hashid::encode(123456);

Hashid::connection('hashids')->decode('X68fkp');
```

And there are also two corresponding helper functions:

- `hashid_encode($data, $name = null)`
- `hashid_decode($data, $name = null)`

```php
hashid_encode(123456);

hashid_decode('TGFyYXZlbA', 'base64');
```

## Built-in Drivers

#### Base62

- Drivers: `base62` , `base62_integer`
- Configuration:
    - `characters` : 62 unique characters
- Backend: [`tuupola/base62`][base62]
- Notes:
    - You may use the `hashid:alphabet` command to generate random characters.
    - [GMP] is strongly recommended as it is much faster than pure PHP.

#### Base64

- Drivers: `base64` , `base64_integer`
- Backend: [`elfsundae/urlsafe-base64`][base64]

#### Hashids

- Drivers: `hashids` , `hashids_hex` , `hashids_integer` , `hashids_string`
- Configuration:
    - `salt`
    - `min_length`
    - `alphabet` : At least 16 unique characters
- Backend: [`hashids/hashids`][hashids]
- Notes:
    - You may use the `hashid:alphabet` command to generate a random alphabet.
    - [GMP] is strongly recommended.

#### Hex

- Drivers: `hex` , `hex_integer`

#### Optimus

- Drivers: `optimus`
- Configuration:
    - `prime` : Large prime number lower than `2147483647`
    - `inverse` : The inverse prime so that `(PRIME * INVERSE) & MAXID == 1`
    - `random` : A large random integer lower than `2147483647`
- Backend: [`jenssegers/optimus`][optimus]
- Notes:
    - You may use the `hashid:optimus` command to generate needed numbers.
    - Only for integer numbers.
    - The max number can be handled correctly is `2147483647`.

## Custom Drivers

To create a custom Hashid driver, you only need to implement the [`ElfSundae\Laravel\Hashid\DriverInterface`](src/DriverInterface.php) interface that contains two methods: `encode` and `decode`. The constructor can optionally receive the driver configuration from a `$config` argument, and type-hinted dependencies injection is supported as well:

```php
<?php

namespace App\Hashid;

use ElfSundae\Laravel\Hashid\DriverInterface;
use Illuminate\Contracts\Encryption\Encrypter;

class CustomDriver implements DriverInterface
{
    protected $encrypter;

    protected $serialize;

    public function __construct(Encrypter $encrypter, array $config = [])
    {
        $this->encrypter = $encrypter;

        $this->serialize = $config['serialize'] ?? false;
    }

    public function encode($data)
    {
        return $this->encrypter->encrypt($data, $this->serialize);
    }

    public function decode($data)
    {
        return $this->encrypter->decrypt($data, $this->serialize);
    }
}
```

Now you can configure the connection with this driver:

```php
'connections' => [

    'custom' => [
        'driver' => App\Hashid\CustomDriver::class,
        'serialize' => false,
    ],

    // ...
]
```

If you prefer a short name for your driver, just register a container binding with `hashid.driver.` prefix:

```php
$this->app->bind('hashid.driver.custom', CustomDriver::class);
```

## Donation 赞赏

If you find my work useful, please consider buying me a cup of coffee :coffee: , all donations are much appreciated :heart:

<p align="center">
    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9CSWKQ4JQYUM4" target="_blank">
        <img src="https://img.0x321.com/images/donate-paypal.jpg">
    </a>
</p>

如果这个轮子对你有用，可否递我一支香烟 :smoking: ？ **赞赏是一种肯定！** 谢谢 :kissing_heart:

<p align="center">
    <img src="https://img.0x321.com/images/donate-alipay-220.png">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <img src="https://img.0x321.com/images/donate-wechat-220.png">
</p>

[base62]: https://github.com/tuupola/base62
[base64]: https://github.com/ElfSundae/urlsafe-base64
[hashids]: https://github.com/ivanakimov/hashids.php
[optimus]: https://github.com/jenssegers/optimus
[gmp]: https://secure.php.net/gmp
