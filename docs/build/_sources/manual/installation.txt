.. title:: Installation: Slick Dependency Injection

Installation
============

`slick/di` is a php 5.5+ library that you’ll have in your project development
environment. Before you begin, ensure that you have PHP 5.5 or higher installed.

You can install `slick/di` with all its dependencies through Composer. Follow
instructions on the `composer website`_ if you don’t have it installed yet.

You can use this Composer command to install `slick/di`:

.. code-block:: bash

    $ composer require slick/di

If you prefer editing your `composer.json` file manually, add `slick/di` to your
`require-dev` section like this:

.. code-block:: javascript

    {
        "require": {
            "slick/di": "^1.0"
        }
    }

Then install `slick/di` with the composer install command:

.. code-block:: bash

    $ composer install

.. _composer website: https://getcomposer.org/download/