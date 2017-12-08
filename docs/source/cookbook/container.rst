.. title:: Container usage: Dependency Injection Container

Container usage
===============

Now that we have defined all of our dependencies its time to create a container
that will use them.

Building a container
--------------------

Even though ``Container`` can be instantiated as normal PHP objects do, it is
advisable to use the ``ContainerBuilder`` factory to do so. It translates the
definitions file(s) into ``DefinitionInterface`` objects that dependency container
uses to resolve its dependencies. It also chains all the created container so that if
you try to get an entry it will search over all containers in the chain. This
is a very handy feature if you want, for example, to create a package that you
will reuse in your applications and in that package you define a container with
default dependencies and later on your application also defines a dependency
container. Definitions can be overridden and the entries from your package
are also available in your application container.

Now lets create our container::

    use Slick\Di\ContainerBuilder;

    $container = (new ContainerBuilder(__DIR__ . '/dependencies.php'))->getContainer();

That's it! We now have a new created dependency container ready to create objects
and inject their dependencies.

Constructor injection
---------------------

Constructor dependency injection is considered the *way* we should do dependency injection
and it is just passing the dependencies as arguments in the constructor. With this you
do not need to created setters and your object is ready to be used right away. You can also
test it in isolation by passing mocks of those dependencies.

Consider the following class::

    class Car
    {
        /**
     * @var EngineInterface
     */
        private $engine;

        public function __construct(EngineInterface $engine)
        {
            $this->engine = $engine;
        }
    }

This is a basic class. The car needs an engine to work, right!?

.. tip::

    In constructor dependency injection and in dependency injection in general, it is a
    good practice to type hint your arguments. This practice guarantees that the arguments
    are from that given type and PHP's core will trigger a fatal error if they are not.

Now that we have a container with all its dependency definitions lets create the car using
and engine that is store in the container under the ``diesel.engine`` name::

    $dieselCar = $container->make(Car::class, '@diesel.engine');

This line of code is instructing the container that it should instantiate a ``Car`` object
and it will inject the dependency that was stored under the ``diesel.engine`` name in its constructor.

Take a look at :doc:`Container class </reference/container>` reference page for more information on
``Container::make()`` method.

Constructor auto-injection
--------------------------

It is also possible to have dependencies injected on objects created by ``Container::make()``
only by type hinting the parameters in the constructor::

    public function __construct(EngineInterface $engine)...
    ...
    $dieselCar = $container->make(Car::class);
    ...
    // As __construct(EngineInterface $engine) is type hinted the container will look
    // for '@EngineInterface::class' definition and inject it.

You can mix the arguments sent on ``Container::make()`` second parameter with constructor
auto-injection. In this case the arguments used in this late array will override the ones
container has figure out.

.. important::

    Since v2.3.0 this is the behavior of ``Container::make()`` method and an exception
    will be thrown whenever a parameter hint results in a missing definition, otherwise
    why should you create object with the dependency container?

Factory method
--------------

one other possibility to create classes that the container can instantiate is by implementing
the ``ContainerInjectionInterface`` interface. This is a simple interface that forces the
creation of the object trough a factory method.

Take a look at the ``Car`` class with dependency injection implementation::

    use Slick\Di\ContainerInjectionInterface;
    use slick\Di\ContainerInterface;

    class Car implements ContainerInjectionInterface
    {
        /**
     * @var EngineInterface
     */
        private $engine;

        public function __construct(EngineInterface $engine)
        {
            $this->engine = $engine;
        }

        /**
     * Creates a diesel car
     *
     * @param ContainerInterface $container
     * @return Car
     */
        public static function create(ContainerInterface $container)
        {
            $car = new Car($container->get('diesel.engine'));
            return $car;
        }
    }

Creating the car::

    $dieselCar = $container->make(Car::class);

The container will call the ``ContainerInjectionInterface::create()`` method passing itself as argument.
Note that the responsibility for object creation is on the class itself.

Form more information check the :doc:`Container Injection Interface </reference/container-injection-interface>` reference page.