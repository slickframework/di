# Slick Di package

[![Latest Version](https://img.shields.io/github/release/slickframework/di.svg?style=flat-square)](https://github.com/slickframework/di/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/slickframework/di/develop.svg?style=flat-square)](https://travis-ci.org/slickframework/di)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/slickframework/di/develop.svg?style=flat-square)](https://scrutinizer-ci.com/g/slickframework/di/code-structure?branch=develop)
[![Quality Score](https://img.shields.io/scrutinizer/g/slickframework/di/develop.svg?style=flat-square)](https://scrutinizer-ci.com/g/slickframework/di?branch=develop)
[![Total Downloads](https://img.shields.io/packagist/dt/slick/di.svg?style=flat-square)](https://packagist.org/packages/slick/di)

`slick/di` is a simple dependency injection container library.  

This package is extracted from [Slick framework](https://github.com/slickframework/slick).

This package is compliant with PSR-2 code standards and PSR-4 autoload standards. It
also applies the [semantic version 2.0.0](http://semver.org) specification.

## Install

Via Composer

``` bash
$ composer require slick/di
```

## Usage

Dependency injection and dependency injection containers are tow different things.
Dependency injection is a design pattern that implements
[inversion of control](https://en.wikipedia.org/wiki/Inversion_of_control) for
resolving dependencies. On the other hand _Dependency Injection Container_ (DiC) is a tool
that will help you create, reuse and inject dependencies.

DiC can also be used to store object instances that you create and values that you may
need to use repeatedly like configuration settings.

Lets look at a very simple example:

```php
use Slick\Di\ContainerBuilder;

$definitions = [
    'config.environment' => 'develop'
];

$container = (new ContainerBuilder($definitions))->getContainer();

print $container->get('config.environment'); // Will print 'develop'
```

In this code example we create a DiC with a single definition under the `config.environment` key/name
and retrieve it latter. For that we use the `ContainerBuilder` factory that need an array or a file
name with an array of definitions that can be use to create our dependencies.

This is not a big deal so far as you are probably saying that you can achieve the same result with a 
global variable. In fact storing values or object instances in a container is the simplest feature
of a DiC and a _side effect_ as the main goal is to store definitions that can be used to create
the real objects when we need them.

But lets first understand what a definition is and how to set them in your container.

## Definitions

---

Definitions are entries in an array that instruct the container on how to create the
correspondent object instance or value.

Every container MUST have a definition list (associative array) to be crated and you
SHOULD always use the `Slick\Di\ContainerBuilder` to create your container.

Lets create our `dependencies.php` file that will contain our dependencies definitions:

```php
<?php

/**
 * Dependency injection definitions file
 */
 
return [
    'timezone' => 'UTC',
    'config' => function() {
        return Configuration::get('config');
    },
]; 
```

<div class="alert alert-info" role="alert">
    <h4>
        <i class="fa fa-info "></i>
        Why use PHP arrays?
    </h4>
    
    This is a very simple answer. If you use other markup/style to create the
    container definitions file, for example <code>.ini</code> or <code>.yml</code>
    you will need to parse those settings and then apply them.<br>
    If you use PHP arrays there is no need to parse it and the code can be directly
    executed, enhancing performance. 
</div>

### Value definitions

---

A value or scalar definition is used as is. The following example is a value definition:
```php
<?php

/**
 * Dependency injection value definition example
 */
return [
    'timezone' => 'UTC'
];
```

Value definitions are good to store application wide constants.

### Callable definitions

---

With callable definitions you can compute and/or control the object or value creation:
```php
<?php

/**
 * Dependency injection callable definition example
 */
return [
    'config' => function() {
        return Configuration::get('config');
    }
];
```

### Alias definitions

---

Alias definitions are a shortcut for already defined entries:

```php
<?php

/**
 * Dependency injection alias definition example
 */
return [
    'config' => @general.config
];
```

The alias points to an entry key and are always prefixed with an `@`

### Object definitions

---

Objects its what makes DiC very handy, and fun! To create an object definition you
need to use an helper class: `Slick\Di\Definition\ObjectDefinition`

Lets see an example:
```php
<?php

use Slick\Di\Definition\ObjectDefinition;

/**
 * Dependency injection object definition example
 */
return [
    'siteName' => 'Example site',
    'config' => function() {
        return Configuration::get('config');
    },
    'search.service' => ObjectDefinition::create('Services\SearchService')
        ->setConstructArgs(['@config'])
        ->setMethod('setMode', ['simple'])
        ->setProperty('siteName', '@siteName')
];
```

To create an object you usually call its constructor with dependent arguments, call some
methods and/or update properties if needed. This is what the 
`Slick\Di\Definition\ObjectDefinition` tries the mimic.

In the above example we use all possible definitions.

## Constructor injection

---

Consider the following class:

```php
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
```

Now lest create a car object using a dependency injection container:

```php
$myCar = $container->get(Car::class);
```

As a rule of thumb, always use type hint in your constructor. This will make your code
more readable, bug free and easy to instantiate with the dependency container.

The dependency container will look to constructor arguments and search for type matches
in the dependencies collection he holds and will inject those dependencies on the requested
object instance.

## Setter injection

---

When creating objects with containers, be aware that the container will also look for methods
like `set<VarName>(VarType $var)` and if it has a matching dependency it will use that method
to inject that dependency.

```php
class Car
{
    /**
     * @var Engine
     */
    protected $engine;
    
    public function setEngine(Engine $engine)
    {
        $this->engine = $engine;
        return $this;
    }
}
```

By calling the container to create your car object like
```php
$myCar = $container->get(Car::class);
```
`Car::$engine` will be inject using the setter method.


<div class="alert alert-warning" role="alert">
    <h4>
        <i class="fa fa-warning "></i>
        Unknown dependencies
    </h4>
    
    When creating objects with a container if you have a setter but there is no known
    dependency in the container collection, it will not fail because the container
    only injects dependencies that he knows of.<br />
    In the other hand if you have dependencies
    on your constructor the object creation will fail due to missing arguments.
</div>

## Property injection

---

In property injection we will use the annotation `@inject` to tell the container
what to inject.

```php
class Car
{
    /**
     * @inject
     * @var Engine
     */
    protected $engine;
}
```

In the example above the container will use the `@var` annotation to determine the
dependency to inject.

```php
class Car
{
    /**
     * @inject car.config
     * @var array
     */
    protected $config;
}
```
`@inject` annotation accepts an argument with the entry name that the container should
use to determine the dependency. In the above example, `config` array was stored with
`car.config` key and will be injected in `Car` creation.

To skip dependency injection on methods or properties you need to set `@ignoreInject`
annotation. This annotation tells dependency container to ignore the automatic dependency
injection on public properties or public methods. This annotation does not work with 
constructor methods.

<div class="alert alert-warning" role="alert">
    <h4>
        <i class="fa fa-warning "></i>
        Missing dependencies
    </h4>
    
    By using the <code>@inject</code> annotation you are explicitly telling the container
    to inject a dependency. If no dependency is found for the provided key or type
    an exception will be thrown telling you that the container cannot inject something
    you said that he has to.
</div>

For a full documentation on this package please visit
[Slick Documentation Site](https://www.slick-framework.com/packages/di).

## Testing

``` bash
$ vendor/bin/phpunit
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