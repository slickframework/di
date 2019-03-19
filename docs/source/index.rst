Slick Dependency Injection
==========================

``slick/di`` is an easy dependency injection container for PHP 7.1+.

It aims to be very lightweight and tries to remove a lot of the *guessing* and *magic*
stuff that dependency containers use those days.

It also allows you to nest containers witch can become very useful if you have several packages that you
reuse in your applications, allowing you to define containers with default
dependencies in those packages for later override and usage them in your application.

There are a lot of implementations of a dependency injection container out there
and some of them are really good. Some examples are the
`Symfony Dependency Injection Component`_, `Zend 2 Dependency Injection`_,
`The PHP league container`_, or `PHP-DI`_ just to name a few.

This implementation is a result of what we thought it was the best to have in a
dependency injection container.


.. toctree::
    :hidden:
    :maxdepth: 2

    manual/getting-started
    cookbook/definitions
    cookbook/container
    reference
    manual/contrib
    manual/license

.. _Symfony Dependency Injection Component: https://symfony.com/doc/current/components/dependency_injection.html
.. _The PHP league container: http://container.thephpleague.com/
.. _Zend 2 Dependency Injection: https://framework.zend.com/manual/2.4/en/modules/zend.di.introduction.html
.. _PHP-DI: http://php-di.org/
