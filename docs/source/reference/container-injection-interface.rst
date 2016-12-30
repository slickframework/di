.. Container Injection interface

Container Injection interface
=============================

.. php:namespace:: Slick\Di

.. php:interface:: ContainerInjectionInterface

    This is an easy way of creating a class that the dependency container will know
    how to instantiate. It has a single method and it enforces the constructor dependency
    injection pattern.

    .. php:staticmethod:: create($container)

        Instantiates a new instance of this class.

        :param Interop\\Container\\ContainerInterface $container: The service container this instance should use.
        :returns: A new instance of this class.

    .. tip::

        This is a factory method that returns a new instance of this class. The
        factory should pass any needed dependencies into the constructor of this
        class, but not the container itself. Every call to this method must return
        a new instance of this class; that is, it may not implement a singleton.