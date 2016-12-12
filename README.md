# Slick Di package

[![Latest Version](https://img.shields.io/github/release/slickframework/di.svg?style=flat-square)](https://github.com/slickframework/di/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/slickframework/di/develop.svg?style=flat-square)](https://travis-ci.org/slickframework/di)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/slickframework/di/develop.svg?style=flat-square)](https://scrutinizer-ci.com/g/slickframework/di/code-structure?branch=develop)
[![Quality Score](https://img.shields.io/scrutinizer/g/slickframework/di/develop.svg?style=flat-square)](https://scrutinizer-ci.com/g/slickframework/di?branch=develop)
[![Total Downloads](https://img.shields.io/packagist/dt/slick/di.svg?style=flat-square)](https://packagist.org/packages/slick/di)

`slick/di` is an easy dependency injection container for PHP 5.5+. It aims to be very
lightweight and tries to remove a lot of the guessing and magic stuff that dependency
containers use those days. It also allows you to nest containers witch can become
very useful if you have several packages that you reuse in your applications, allowing
you to define containers with default dependencies in those packages overriding and
using them in your application.

This package is compliant with PSR-2 code standards and PSR-4 autoload standards. It
also applies the [semantic version 2.0.0](http://semver.org) specification.

## Install

Via Composer

``` bash
$ composer require slick/di
```

## Usage

To create a dependency container lets create a ``services.php`` file with all our
dependency definitions:

``` php
    use Slick\Configuration\Configuration:
    use Slick\Di\Definition\ObjectDefinition;

    /**
     * Dependency injection object definition example
     */
    return [
        'config' => function() {
            return Configuration::get('config');
        },
        Engine::class => ObjectDefinition::create(Engine::class)
            ->setConstructArgs(['@config'])
            ->setMethod('setMode', ['simple'])
    ];
```

Create a dependency container with ``ContainerBuilder``:

``` php
    use Slick\Di\ContainerBuilder;

    $container = (new ContainerBuilder(__DIR__ . '/services.php'))->getContainer();
```

Now you are ready to create and inject dependencies with your container:

``` php
    class Car
    {
        /**
         * @var Engine
         */
        protected $engine;

        public function __construct(Engine $engine)
        {
            $this->engine = $engine;
        }
    }

    $myCar = $container->get(Car::class);
```

Please refer to the [full documentation site](http://di.slick-framework.com) for more on ``slick/di`` package.

## Testing

We use [Behat](http://behat.org/en/latest/index.html) to describe features and and for acceptance tests
and [PHPUnit](https://phpunit.de) for integration and unit testing.

``` bash
    # unit and integration tests
    $ vendor/bin/phpunit

    # acceptance tests
    $ vendor/bin/behat
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email silvam.filipe@gmail.com instead of using the issue tracker.

## Credits

- [Slick framework](https://github.com/slickframework)
- [All Contributors](https://github.com/slickframework/database/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.