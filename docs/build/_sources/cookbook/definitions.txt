.. title:: Definitions: Dependency Injection Container

Definitions
===========

What's a definition?
--------------------
Definitions are entries in an array that instruct the container on how to
create the correspondent object instance or value.

.. attention::

    Every container MUST have a definition list (associative array) in order to be
    created and you SHOULD always use the ``Slick\Di\ContainerBuilder`` to create
    your container.

Lets create our ``dependencies.php`` file that will contain our dependencies
definitions:

.. code-block:: php

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

    /**
     * Dependency injection value definition example
     */
    $services['timezone'] = 'UTC';

    return $services;

Value definitions are good to store application wide constants.

Factory definition
------------------
With factory definition we can compute and/or control the object or value creation::

    /**
     * Dependency injection callable definition example
     */
    $services['general.config'] = function() {
            return Configuration::get('config');
        }

    return $services;

Alias definition
----------------
Alias definition is a shortcut for another defined entry::

    /**
     * Dependency injection alias definition example
     */
    $services['config'] = '@general.config';

    return $services;

The alias points to an entry key and is always prefixed with an ``@``

Object definition
-----------------
Objects are what makes dependency containers very handy, and fun! Lets take
a look on an object definition inside our ``dependencies.php`` file::


    namespace Services;

    use Services\SearchService;
    use Slick\Configuration\Configuration:
    use Slick\Di\Definition\Object;

    /**
     * Dependency injection object definition example
     */
    $services['siteName'] => 'Example site';
    $services['config'] => function() {
        return Configuration::get('config');
    };

    // Object definition
    $services['search.service'] = Object::create(SearchService::class)
        ->with('@config')
        ->call('setMode')->with('simple')
        ->call('setSiteName')->with('@siteName')
        ->assign(20)->to('rowsPerPage')
    ;

    return $services;

You can use the alias notation to instruct container to use other entries when
creating those objects.