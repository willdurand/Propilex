Propilex
========

A [Silex](http://silex.sensiolabs.org) application which uses [Propel](http://propelorm.org), [Backbone](http://backbonejs.org/).


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


Credits
-------

Inspirated by: http://www.jamesyu.org/2011/01/27/cloudedit-a-backbone-js-tutorial-by-example/
