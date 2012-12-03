Propilex
========

A [Silex](http://silex.sensiolabs.org) application which uses
[Propel](http://propelorm.org), [Backbone.JS](http://backbonejs.org/), but also
[Bower](http://twitter.github.com/bower/) as browser package manager,
[RequireJS](http://requirejs.org/), [Garlic.js](garlicjs.org), [Twitter
Bootstrap](http://twitter.github.com/bootstrap/) and [Less CSS](http://lesscss.org/).


Installation
------------

Install PHP dependencies:

    php composer.phar install


And browser dependencies using [Bower](http://twitter.github.com/bower/):

    cd web && bower install
    cd ..


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


Screenshots
-----------

![](https://raw.github.com/willdurand/Propilex/master/doc/screenshot_1.png)

![](https://raw.github.com/willdurand/Propilex/master/doc/screenshot_2.png)


Credits
-------

Inspired by: http://www.jamesyu.org/2011/01/27/cloudedit-a-backbone-js-tutorial-by-example/
