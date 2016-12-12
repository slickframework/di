.. title:: Definitions: Dependency Injection Container

Definitions
===========

Definitions are entries in an array that instruct the container on how to
create the correspondent object instance or value.

Every container MUST have a definition list (associative array) to be
created and you SHOULD always use the ``Slick\Di\ContainerBuilder`` to create
your container.

Lets create our ``dependencies.php`` file that will contain our dependencies
definitions:

.. code-block:: php

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

.. note:: Why use PHP arrays?

    This question has a very simple answer. If you use other markup/style to
    create the container definitions file, for example ``.ini`` or ``.yml``
    you will need to parse those settings and then apply them.
    If you use PHP arrays there is no need to parse it and the code can be
    directly executed, enhancing performance.

Value definition
----------------
A value or scalar definition is used as is. The following example is a value definition::

    <?php

    /**
     * Dependency injection value definition example
     */
    return [
        'timezone' => 'UTC'
    ];

Value definitions are good to store application wide constants.

Factory (callable) definition
-----------------------------
With factory definition you can compute and/or control the object or value creation::

    <?php

    /**
     * Dependency injection callable definition example
     */
    return [
        'config' => function() {
            return Configuration::get('config');
        }
    ];

Alias definition
----------------
Alias definition is a shortcut for another defined entry::

    <?php

    /**
     * Dependency injection alias definition example
     */
    return [
        'config' => @general.config
    ];

The alias points to an entry key and is always prefixed with an ``@``

Object definition
-----------------
Objects are what makes dependency containers very handy, and fun! To create an
object definition you need to use an helper
class: ``Slick\Di\Definition\ObjectDefinition``

Lets see an example::

    <?php

    use Services\SearchService;
    use Slick\Configuration\Configuration:
    use Slick\Di\Definition\ObjectDefinition;

    /**
     * Dependency injection object definition example
     */
    return [
        'siteName' => 'Example site',
        'config' => function() {
            return Configuration::get('config');
        },
        'search.service' => ObjectDefinition::create(SearchService::class)
            ->setConstructArgs(['@config'])
            ->setMethod('setMode', ['simple'])
            ->setProperty('siteName', '@siteName')
    ];

You can use the alias notation to instruct container to use other entries when
creating those objects.