Slick Dependency Injection
==========================

``slick/di`` is an easy dependency injection container for PHP 5.5+. It aims to
be very lightweight and tries to remove a lot of the *guessing* and *magic*
stuff that dependency containers use those days. It also allows you to nest
containers witch can become very useful if you have several packages that you
reuse in your applications, allowing you to define containers with default
dependencies in those packages overriding and using them in your application.

There are a lot of implementations of a dependency injection container out there
and some of them are really good. Some examples are the
`Symfony Dependency Injection Component`_, `Zend 2 Dependency Injection`_,
`The PHP league container`_, or `PHP-DI`_ just to name a few.

Dependency injection container quick start
------------------------------------------

To create a dependency container lets create a ``services.php`` file with all our
dependency definitions::

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

Create a dependency container with ``ContainerBuilder``::

    use Slick\Di\ContainerBuilder;

    $container = (new ContainerBuilder(__DIR__ . '/services.php'))->getContainer();

Now you are ready to create and inject dependencies with your container::

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

That's it!

User guide
----------

.. toctree::
    :maxdepth: 2

    manual/overview
    manual/installation
    cookbook/definitions
    cookbook/container
    manual/contrib
    manual/license

.. _Symfony Dependency Injection Component: https://symfony.com/doc/current/components/dependency_injection.html
.. _The PHP league container: http://container.thephpleague.com/
.. _Zend 2 Dependency Injection: https://framework.zend.com/manual/2.4/en/modules/zend.di.introduction.html
.. _PHP-DI: http://php-di.org/