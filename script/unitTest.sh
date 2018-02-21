#! /bin/bash

./vendor/symfony/phpunit-bridge/bin/simple-phpunit -c app/phpunit.xml.dist -vvv --debug $@
