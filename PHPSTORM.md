# PHPStorm integration

## Configuring PHPStorm for PHP interpreter with XDebug

Unit tests are usually run on Vagrant, however, to speed up the process (mounting through NFS slows down the runner significantly) and to run all modules with one configuration it is also possible to run unit tests with local php interpreter. 

1. Make sure you have php 5.5 installed locally at at your box. ```php -v``` to see the version. If not, you can install it with https://github.com/Homebrew/homebrew-php
2. Make sure your *XDebug* is on. Run ```php -v``` to check if it is installed. You should see the line ```with Xdebug v2.X.X, Copyright (c) 2002-2015, by Derick Rethans```. If not, you can install it with brew: ```brew install php55-xdebug```
3. Open PHPStorm, then Preferences > Languages And Frameworks > PHP
4. Set PHP Language Level to 5.5
5. Set Interpreter to point to where your PHP interpreter is installed. You can run ```which php``` to find out the path.

## Configuring PHPStorm for running unit tests

1. Download PHPUnit from https://phar.phpunit.de/phpunit-old.phar
2. Open PHPStorm, then Preferences > Languages and Frameworks > PHP > PHPUnit > PHPUnit Library
3. Set *Path to phpunit.phar*
4. Set *Default bootstrap file* to ```$WORKSPACE/config/phpstorm/unit-test-bootstrap-proxy/proxy.php```
5. From now on you should be able to run, debug and calculate coverage for unit tests in PHPStorm: https://www.jetbrains.com/help/phpstorm/2016.1/testing-php-applications.html?origin=old_help
