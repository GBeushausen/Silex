PrestoPHP, a simple Web Framework
=============================

**WARNING**: PrestoPHP is a work in Progress right now. While the Microservice part is stable other parts might not be ready for live usage.

PrestoPHP is a PHP micro-framework to develop websites and API's based on `Symfony
components`_:

.. code-block:: php

    <?php

    require_once __DIR__.'/../vendor/autoload.php';

    $app = new PrestoPHP\Application();

    $app->get('/hello/{name}', function ($name) use ($app) {
      return 'Hello '.$app->escape($name);
    });

    $app->run();

PrestoPHP works with PHP 7.1.3 or later. PrestoPHP's goal is to offer a framework suitable for commercial software development. This means it's offers
long time support and is always supporting PHP versions back to the latest Ubuntu Server LTS version. PrestoPHP will also support code generation in the
future similar to CakePHPs bake plugin.

Installation
------------

The recommended way to install PrestoPHP is through `Composer`_:

.. code-block:: bash

    composer require PrestoPHP/PrestoPHP "~2.0"

Alternatively, you can download the `PrestoPHP.zip`_ file and extract it.

More Information
----------------

Read the `documentation`_ for more information and `changelog
<doc/changelog.rst>`_ for upgrading information.

Tests
-----

To run the test suite, you need `Composer`_ and `PHPUnit`_:

.. code-block:: bash

    composer install
    phpunit

Support
-------

If you have a configuration problem use the `PrestoPHP tag`_ on StackOverflow to ask a question.

If you think there is an actual problem in PrestoPHP, please `open an issue`_ if there isn't one already created.

License
-------

PrestoPHP is licensed under the MIT license.

.. _Symfony components: https://symfony.com
.. _Composer:           https://getcomposer.org
.. _PHPUnit:            https://phpunit.de
.. _PrestoPHP.zip:      https://PrestoPHP.symfony.com/download
.. _documentation:      https://PrestoPHP.symfony.com/documentation
.. _PrestoPHP tag:      https://stackoverflow.com/questions/tagged/PrestoPHP
.. _open an issue:      https://github.com/PrestoPHPphp/PrestoPHP/issues/new
