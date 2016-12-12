.. title:: Overview: Slick Dependency Injection

Overview
========

Dependency Injection
--------------------
Dependency injection is a concept that has been talked about all over the web.
You probably have done it without knowing that is called dependency injection.
Simply put the next line of code can describe what it is::

    $volvo = new Car(new Engine());

Above, ``Engine`` is a dependency of ``Car``, and ``Engine`` was injected into
``Car``. If you are not familiar with Dependency Injection please read this
Fabien Pontencier's `great series about Dependency injection`_.


Dependency Injection Container
------------------------------
Dependency injection and dependency injection containers are tow different
things. Dependency injection is a design pattern that implements
`inversion of control`_ for resolving dependencies. On the other hand
Dependency Injection Container is a tool that will help you create, reuse
and inject dependencies.

A dependency container can also be used to store object instances that you
create and values that you may need to use repeatedly like configuration
settings.

Lets look at a very simple example::

    use Slick\Di\ContainerBuilder;

    $definitions = [
        'config.environment' => 'develop'
    ];

    $container = (new ContainerBuilder($definitions))->getContainer();

    print $container->get('config.environment'); // Will output 'develop'


In this code example we create a dependency container with a single definition
under the ``config.environment`` key/name and retrieve it latter. For that we use
the ``ContainerBuilder`` factory that need an array or a file name with an
associative array of definitions that can be used to create our dependencies.

This is not a big deal so far as you are probably saying that you can achieve
the same result with a global variable. In fact storing values or object
instances in a container is the simplest feature of a dependency container
and a side effect as the main goal is to store definitions that can be used
to create the real objects when we need them.

But lets first understand :doc:`what a definition is </cookbook/definitions>` and how to set them in your
container.

.. _great series about Dependency injection: http://fabien.potencier.org/what-is-dependency-injection.html
.. _inversion of control: https://en.wikipedia.org/wiki/Inversion_of_control