.. title:: Container usage: Dependency Injection Container

Container usage
===============

Now that we have defined all of our dependencies its time to create a container
that will use them.

Even though ``Container`` can be instantiated as normal PHP objects do, it is
advisable to use the ``ContainerBuilder`` factory to do so. It translates the
definitions file into ``DefinitionInterface`` objects that dependency container
uses to create dependencies. It also chains all the created container so that if
you try to get an entry it will search over all containers in the chain. This
is a very handy feature if you want, for example, to create a package that you
will reuse in your applications and in that package you define a container with
default dependencies and later on your application also defines a dependency
container. Definitions can be overridden and the entries from your package
are also available in your application container.

Now lets create our container::

    use Slick\Di\ContainerBuilder;

    $container = (new ContainerBuilder(__DIR__ . '/services.php'))->getContainer();

That's it! We now have a new created dependency container ready to create objects
and inject their dependencies.

Constructor injection
---------------------

Consider the following class::

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

Now lets create a new ``Car`` using our dependency container::

    $myCar = $container->get(Car::class);

.. tip::

    As a rule of thumb, always use type hint in your constructor. This will make
    your code more readable, bug free and easy to instantiate with the
    dependency container.

The dependency container will look to constructor arguments and search for type
matches in the dependencies collection he holds and will inject those
dependencies on the requested object instance.

Setter injection
----------------

When creating objects with containers, be aware that the container will also
look for methods like ``set<VarName>(VarType $var)`` and if it has a matching
dependency it will use that method to inject that dependency::

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

By calling the container to create your car object like::

    $myCar = $container->get(Car::class);

``Car::$engine`` will be inject using the setter method.

.. warning::

    When creating objects with a container if you have a setter but there is no
    known dependency in the container collection, it will not fail because the
    container only injects dependencies that he knows of.

    In the other hand if you have dependencies on your constructor the object
    creation will fail due to missing arguments.

Property injection
------------------

In property injection we will use the annotation ``@inject`` to tell the
container what to inject::

    class Car
    {
        /**
         * @inject
         * @var Engine
         */
        protected $engine;
    }

In the example above the container will use the ``@var`` annotation to determine
the dependency to inject::

    class Car
    {
        /**
         * @inject car.config
         * @var array
         */
        protected $config;
    }

``@inject`` annotation accepts an argument with the entry name that the container
should use to determine the dependency. In the above example, config array was
stored with car.config key and will be injected in ``Car`` creation.

.. note::

    To skip dependency injection on methods or properties you need to set
    ``@ignoreInject`` annotation. This annotation tells dependency container to
    ignore the automatic dependency injection on public properties or public
    methods. This annotation does not work with constructor methods.

.. attention::

    By using the ``@inject`` annotation you are explicitly telling the container
    to inject a dependency. If no dependency is found for the provided key or
    type an exception will be thrown telling you that the container cannot
    inject something you said that he has to.
