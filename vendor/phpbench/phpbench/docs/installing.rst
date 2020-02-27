Installing
==========

PHPBench can be installed either as dependency for your project or as a global
package.

Install as a PHAR package
-------------------------

Installing as a PHAR is convenient, you will need to download the
phar_ and the `public key`_, this can be
done with CURL as follows:

.. code-block:: bash

    $ curl -o phpbench.phar https://phpbench.github.io/phpbench/phpbench.phar
    $ curl -o phpbench.phar.pubkey https://phpbench.github.io/phpbench/phpbench.phar.pubkey

You will probably then want make it executable and put it in your systems
global path, on Linux systems:

.. code-block:: bash

    $ chmod 0755 phpbench.phar
    $ sudo mv phpbench.phar /usr/local/bin/phpbench
    $ sudo mv phpbench.phar.pubkey /usr/local/bin/phpbench.pubkey

You can update the version at any time by using the ``self-update`` command:

.. code-block:: bash

    $ phpbench self-update

.. warning::

    PHPBench is unstable, installing as a PHAR means that you are always
    updating to the latest version, the latest version may include BC breaks.
    Therefore it is recommended to include the package as a project dependency
    for continuous-integration.

Composer Install
----------------

To install PHPBench as a dependency of your project:

.. code-block:: php

    $ composer require phpbench/phpbench @dev --dev


You may then run PHPBench from your project's directory as follows:

.. code-block:: bash

    $ ./vendor/bin/phpbench

Composer Global Install
-----------------------

You may install `PHPBench globally`_ using composer:

.. code-block:: php

    $ composer global require phpbench/phpbench @dev

.. note::

    You will need to add Composer's global ``bin`` directory to your systems
    ``PATH`` variable (on linux). See the above link.

You may now run PHPBench simply as:

.. code-block:: bash

    $ phpbench

.. _PHPBench globally: http://akrabat.com/global-installation-of-php-tools-with-composer/
.. _phar: https://phpbench.github.io/phpbench/phpbench.phar
.. _public key: https://phpbench.github.io/phpbench/phpbench.phar.pubkey
