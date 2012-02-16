Propilex
========

A [Silex](http://silex.sensiolabs.org) application which uses [Propel](http://propelorm.org).


Installation
------------

Install dependencies:

    git submodule update --init


Build Model classes, SQL, and the configuration:

    ./vendor/propel/generator/bin/propel-gen config main


Configure a database:

    mysql -uroot -e 'CREATE DATABASE propilex'

    mysql -uroot propilex < config/sql/Propilex.Model.schema.sql


You're done!
