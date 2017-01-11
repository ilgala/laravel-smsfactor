# Laravel SMSFactor

Laravel SMSFactor was created by, and is maintained by [Filippo Galante](https://github.com/IlGala), and is a [PHP SMSFactor API](https://www.smsfactor.it/docs/API/SMSFactor-DOC-API%20IT%20V3.pdf) bridge for [Laravel 5](http://laravel.com). The source code is inspired by the repositories created by [Graham Campbell](https://github.com/GrahamCampbell) and utilises his [Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) package. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/GrahamCampbell/Laravel-GitHub/releases), [license](LICENSE), and [contribution guidelines](CONTRIBUTING.md). In order to send SMSs you have to create a [SMSFactor](https://www.smsfactor.com/) account, and you'll be free to try all its features. 

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![StyleCI][ico-style]](link-style)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

## Installation

Either [PHP](https://php.net) 5.5+ or [HHVM](http://hhvm.com) 3.6+ are required.

To get the latest version of Laravel SMSFactor, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require IlGala/laravel-smsfactor
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

```json
{
    "require": {
        "IlGala/laravel-smsfactor": "^1.0"
    }
}
```

You will also need to install at least one of the following dependencies for each driver:

* The buzz connector requires `"kriswallsmith/buzz": "^0.15"` in your `composer.json`.
* The guzzle connector requires `"guzzle/guzzle": "^3.7"` in your `composer.json`.
* The guzzlehttp connector requires `"guzzlehttp/guzzle": "^5.0"` or `"guzzlehttp/guzzle": "^6.0"` in your `composer.json`.

Once Laravel SMSFactor is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

* `'IlGala\SMSFactor\SMSFactorServiceProvider'`

You can register the SMSFactor facade in the `aliases` key of your `config/app.php` file if you like.

* `'SMSFactor' => 'IlGala\SMSFactor\Facades\SMSFactor'`


## Configuration

Laravel SMSFactor requires connection configuration.

To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish
```

This will create a `config/smsfactor.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

There are two config options:

##### Default Connection Name

This option (`'default'`) is where you may specify which of the connections below you wish to use as your default connection for all work. Of course, you may use many connections at once using the manager class. The default value for this setting is `'main'`.

##### SMSFactor Connections

This option (`'connections'`) is where each of the connections are setup for your application. Example configuration has been included, but you may add as many connections as you would like.


## Usage

##### SMSFactorManager

This is the class of most interest. It is bound to the ioc container as `'smsfactor'` and can be accessed using the `Facades\SMSFactor` facade. This class implements the `ManagerInterface` by extending `AbstractManager`. The interface and abstract class are both part of [Graham Campbell Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) package, so you may want to go and checkout the docs for how to use the manager class over at [that repo](https://github.com/GrahamCampbell/Laravel-Manager#usage).

##### Facades\SMSFactor

This facade will dynamically pass static method calls to the `'smsfactor'` object in the ioc container which by default is the `SMSFactorManager` class.

##### SMSFactorServiceProvider

This class contains no public methods of interest. This class should be added to the providers array in `config/app.php`. This class will setup ioc bindings.

##### Real Examples

Here you can see an example of just how simple this package is to use. Out of the box, the default adapter is `main`. After you enter your authentication details in the config file, it will just work:

```php
use IlGala\SMSFactor\Facades\SMSFactor;
// you can alias this in config/app.php if you like

$total_credits = SMSFactor::credits();
// Check SMS Factor documentation for more information about the results:
/*
 * $total_credits (JSON format):
 * {
 *  "credits": "2420",
 *  "message": "OK"
 * }
 *
 * $total_credits (XML format):
 * <?xml version="1.0" encoding="UTF-8"?>
 * <response>
 *  <credits>2420</credits>
 *  <message>OK</message>
 * </response>
 */
```

The smsfactor manager will behave like it is a `\SMSFactor\SMSFactor` class. If you want to call specific connections, you can do with the `connection` method:

```php
use IlGala\SMSFactor\Facades\SMSFactor;

// the alternative connection is the other example provided in the default config
SMSFactor::connection('alternative')->credits()->credits;

// let's check how long we have until the limit will reset
SMSFactor::connection('alternative')->credits()->credits;
```

With that in mind, note that:

```php
use IlGala\SMSFactor\Facades\SMSFactor;

// writing this:
SMSFactor::connection('main')->credits();

// is identical to writing this:
SMSFactor::credits();

// and is also identical to writing this:
SMSFactor::connection()->credits();

// this is because the main connection is configured to be the default
SMSFactor::getDefaultConnection(); // this will return main

// we can change the default connection
SMSFactor::setDefaultConnection('alternative'); // the default is now alternative
```

If you prefer to use dependency injection over facades like me, then you can easily inject the manager like so:

```php
use IlGala\SMSFactor\SMSFactorManager;
use Illuminate\Support\Facades\App; // you probably have this aliased already

class SMSSender
{
    protected $smsfactor;

    public function __construct(SMSFactorManager $smsfactor)
    {
        $this->smsfactor = $smsfactor;
    }

    public function sendSms($params, $method, $simulate = false)
    {
        $this->smsfactor->send($params, $method, $simulate = false);
    }
}

App::make('SMSSender')->bar();
```

For more information on how to use the `\SMSFactor\SMSFactor` class we are calling behind the scenes here, check out the [SMSFactor API doc](https://www.smsfactor.it/docs/API/SMSFactor-DOC-API%20IT%20V3.pdf), and the manager class at https://github.com/GrahamCampbell/Laravel-Manager#usage.

##### Further Information

There are other classes in this package that are not documented here. This is because they are not intended for public use and are used internally by this package.


## Security

If you discover a security vulnerability within this package, please send an e-mail to Filippo Galante at filippo.galante@b-ground.com. All security vulnerabilities will be promptly addressed.


## License

Laravel SMSFactor is licensed under [The MIT License (MIT)](LICENSE).

[ico-version]: https://img.shields.io/packagist/v/IlGala/laravel-smsfactor.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-style]: https://styleci.io/repos/78115500/shield?branch=develop
[ico-travis]: https://img.shields.io/travis/IlGala/laravel-smsfactor/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/IlGala/laravel-smsfactor.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/IlGala/laravel-smsfactor.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/IlGala/laravel-smsfactor.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/IlGala/laravel-smsfactor
[link-style]: https://styleci.io/repos/78115500
[link-travis]: https://travis-ci.org/IlGala/laravel-smsfactor
[link-scrutinizer]: https://scrutinizer-ci.com/g/IlGala/laravel-smsfactor/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/IlGala/laravel-smsfactor
[link-downloads]: https://packagist.org/packages/IlGala/laravel-smsfactor
[link-author]: https://github.com/IlGala
[link-contributors]: ../../contributors