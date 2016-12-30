.. Object definition API

ObjectDefinition class
======================

.. php:namespace:: Slick\Di\Definition

.. php:class:: ObjectDefinition

    An object instantiation definition that wraps the necessary steps so that a dependency
    container can instantiate a given class.

    .. php:method:: __construct($className)

        Creates a definition for provided class name.

        :param string $className: The class name that will be instantiated.

    .. php:staticmethod:: create($className)

        Factory method to create an object definition.

        :param string $className: The class name that will be instantiated.
        :returns: ObjectDefinition definition object

    .. php:method:: with(...$arguments)

        Set the arguments for the last defined method call. If no method call was defined yet
        it will set the constructor argument list

        :param array $arguments: Arguments passed to object constructor
        :returns: The object definition itself. Useful for other method calls.

    .. php:method:: withConstructorArgument(...$arguments)

        Set the arguments used to create the object.

        :param array $arguments: Arguments passed to object constructor.
        :returns: The object definition itself. Useful for other method calls.

    .. php:method:: call($methodName)

        Define a method call in the freshly created object.

        :param string $methodName: The method name to call.
        :returns: The object definition itself. Useful for other method calls.

    .. php:method:: assign($value)

        Set the value that will be assigned to a property.

        :param mixed $value: The value to be assigned.
        :returns: The object definition itself. Useful for other method calls.

    .. php:method:: to($propertyName)

        Assign the last defined value to the provided property. The value will be reset
        after its assigned.

        :param string $property: The property name where last value will be assigned.
        :returns: The object definition itself. Useful for other method calls.

    .. php:method:: callMethod($methodName, ...$arguments)

        Define a method call to the method with provided name.

        :param string $methodName: The method name to call.
        :param array $arguments: The list of arguments to use when calling the method.
        :throws: Slick\\Exception\\MethodNotFoundException if called method does not exists.
        :returns: The object definition itself. Useful for other method calls.

    .. php:method:: assignProperty($name, $value)

        Assigns a value to the property with provided name.

        :param string $name: The property name.
        :param mixed  $value: The value to assign to the property.
        :returns: The object definition itself. Useful for other method calls.

    .. php:method:: resolve()

        Resolves the definition into an object.

        :returns: The object as described in the definition