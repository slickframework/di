# Slick Di package

[![Latest Version](https://img.shields.io/github/release/slickframework/di.svg?style=flat-square)](https://github.com/slickframework/di/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/github/actions/workflow/status/slickframework/di/continuous-integration.yml?style=flat-square)](https://github.com/slickframework/di/actions/workflows/continuous-integration.yml)
[![Quality Score](https://img.shields.io/scrutinizer/g/slickframework/di/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/slickframework/di?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/slick/di.svg?style=flat-square)](https://packagist.org/packages/slick/di)

``slick/di`` is an easy dependency injection container for PHP 8.0+.

It aims to be very lightweight and tries to remove a lot of the *guessing* and *magic*
stuff that dependency containers use those days.

It also allows you to nest containers witch can become very useful if you have several packages that you
reuse in your applications, allowing you to define containers with default
dependencies in those packages for later override and usage them in your application.

This package is compliant with PSR-2 code standards and PSR-4 autoload standards. It
also applies the [semantic version 2.0.0](http://semver.org) specification.

## Install

Via Composer

``` bash
$ composer require slick/di
```

## Usage

To create a dependency container we need to create at least a ``services.php``
file with all our dependency definitions:

```php
use Slick\Di\Container;
use Slick\Di\Definition\ObjectDefinition;

/**
 * Dependency injection object definition example
 */
return [
    'config' => [
        'color' => 'blue',
        'gear' => 'manual'
    ],
    
    'engineService' => ObjectDefinition::create(Engine::class)
        ->with('@config')
        ->call('setMode')->with('simple'),
        
    CarSettings::class => function(Container $container) {
        return new CarSettings($container->get('config'));
    }
];
```
Now to build the dependency container we need to use the ``ContainerBuilder`` factory class like this:

```php
use Slick\Di\ContainerBuilder;

$definitionsFile = __DIR__ . '/services.php';
$sources = dirname(__DIR__, 2). '/src';
$container = (new ContainerBuilder($definitionsFile))
    withSources($sources)->getContainer();
```

With that, we are ready to create and inject dependencies with our container:

```php
class Car
{
    /**
     * @var EngineInterface
     */
    protected $engine;
    
    
    public function __construct(
        #[UserService(id: CarSettings::class)]
        CarSettings $settings
    ) {
        // $settings will be injected if created with the Container::make() method.
    }

    /**
     * @inject engineService
     *
     * @return self
     */
    #[CallWith(service: "engineService")]
    public function setEngine(EngineInterface $engine)
    {
        $this->engine = $engine;
        return $this;
    }
}

$myCar = $container->make(Car::class);
```

Please refer to the [full documentation site](http://di.slick-framework.com) for more on ``slick/di`` package.

## Testing

We use [Behat](http://behat.org/en/latest/index.html) to describe features and for acceptance tests
and [PHPSpec](http://www.phpspec.net/) for unit testing.

``` bash
# unit tests
$ vendor/bin/phpspec

# acceptance tests
$ vendor/bin/behat
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email slick.framework@gmail.com instead of using the issue tracker.

## Credits

- [Slick framework](https://github.com/slickframework)
- [All Contributors](https://github.com/slickframework/di/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
