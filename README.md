Propilex
========

A [Silex](http://silex.sensiolabs.org) application which uses [Propel](http://propelorm.org), [Backbone](http://backbonejs.org/).


Installation
------------

Install dependencies:

    php composer.phar install


Build Model classes, SQL, and the configuration:

    bin/bootstrap


Configure a database:

    mysql -uroot -e 'CREATE DATABASE propilex'

    mysql -uroot propilex < app/config/sql/Propilex.Model.schema.sql


You're done!


Configuration
-------------

All the configuration is located in the `app/config/` directory.

* `runtime-conf.xml` contains the database configuration, if you modify it, don't forget to rebuild things by using the previous command;
* `config.php` you should **not** edit this file, except to turn on/off debugging stuffs.


Credits
-------

Inspired by: http://www.jamesyu.org/2011/01/27/cloudedit-a-backbone-js-tutorial-by-example/
