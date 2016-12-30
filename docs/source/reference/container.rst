.. Container reference

Container class
===============

.. php:namespace:: Slick\Di

.. php:class:: Container

    Container is where your dependencies live. It holds a list of ``DefinitionInterface`` objects
    that can be resolved into objects or values.

.. php:method:: get($id)

    Finds an entry of the container by its identifier and returns it.

    :param string $id: Identifier of the entry to look for.
    :throws NotFoundException: No entry was found for this identifier.
    :returns: An object or value that was stored or has its definition under the provided ``$id`` identifier.

.. php:method:: has($id)

    Returns true if the container can return an entry for the given identifier

    :param string $id: Identifier of the entry to look for.
    :returns: True if container can return an entry or false otherwise.

.. php:method:: register($name, $definition [, $scope, $params])

    Adds a definition or a value to the container with the ``$name`` as identifier.

    :param string $name: Identifier where the entry will be stored in.
    :param mixed $definition: The definition or value to store.
    :param string|Scope $scope: The resolution scope. Please see :doc:`Definitions page </cookbook/definitions>` for details. Defaults to ``Scope::SINGLETON``.
    :param array $params: An array of arguments to pass to callable (Factory) definitions. Defaults to an empty array.
    :returns: Container itself. Useful for chaining more container calls.

    .. tip::

        ``$definition`` can be any value and the container will evaluate it in order to determine
        what strategy it will use to resolve it latter on. The possibilities are:

        * **scalar|objects:** scalar values or objects are store as :php:class:`Value` definition;
        * **@<identifier>:** this is an :php:class:`Alias` definition that will point to the
          definition stored under the *identifier* name;
        * **callable:** a callable is stored as :php:class:`Factory` definition. It will be executed
          when ``Container::get()`` is called for the first time and the result value will
          be returned in the subsequent calls. ``$params`` will be passed when executing the callable;
        * ``Slick\Di\DefinitionInterface``: Definition interface handle the entry resolution. In
          this case the container will return the resolved value of the definition.

.. php:method:: make($className[, ...$arguments])

    Creates an instance of provided class injecting its dependencies.

    :param string $className: The class name that the container will use to create the object.
    :param array $arguments: An optional list of arguments to pass to the constructor.
    :returns: An object from the type passed in the ``$className`` argument.

    .. tip::

        If you create a class that implements the ``Slick\Di\ContainerInjectionInterface`` all the ``$arguments`` that
        you may pass to this method will be ignored as the container will call the ``create()`` method and pass himself
        as an argument to that method. Please see :doc:`ContainerInjectionInterface reference </reference/container-injection-interface>`

        All other classes will be resolved with an ``Object`` definition and if you use any ``@inject`` annotation the
        correspondent dependency will be injected.

        Entry alias (``@alias``) can be used in the arguments to reference injected dependencies.