Propilex
========

[![Build Status](https://secure.travis-ci.org/willdurand/Propilex.png?branch=master)](https://travis-ci.org/willdurand/Propilex)

A [Silex](http://silex.sensiolabs.org) application which uses
[Propel](http://propelorm.org),
[Backbone.JS](http://backbonejs.org/), but also:

* [Bower](http://twitter.github.com/bower/) as browser package manager;
* [RequireJS](http://requirejs.org/);
* [Garlic.js](garlicjs.org);
* [Moment.js](http://momentjs.com/);
* [Twitter Bootstrap](http://twitter.github.com/bootstrap/);
* [Less CSS](http://lesscss.org/);
* [Backbone.Forms](https://github.com/powmedia/backbone-forms);
* [Keymaster](https://github.com/madrobby/keymaster).


Installation
------------

Install PHP dependencies:

    php composer.phar install


And browser dependencies using [Bower](http://twitter.github.com/bower/):

    cd web && bower install
    cd ..


Build Model classes, SQL, and the configuration:

    cp app/config/runtime-conf.xml.dist app/config/runtime-conf.xml
    bin/bootstrap


Configure a database:

    mysql -uroot -e 'CREATE DATABASE propilex'

    mysql -uroot propilex < app/config/sql/Propilex.Model.schema.sql


You're done! You can run the application using the PHP built-in webserver:

    php -S 0.0.0.0:4000 -t web/

Open `http://localhost:4000/` in your browser to see Propilex running.


Configuration
-------------

All the configuration is located in the `app/config/` directory.

* `runtime-conf.xml` contains the database configuration, if you modify it, don't forget to rebuild things by using the previous command;
* `build.properties` you should **not** edit this file, except to change the database vendor (`mysql` by default);
* `config.php` you should **not** edit this file, except to turn on/off debugging stuffs.


Screenshots
-----------

![](https://raw.github.com/willdurand/Propilex/master/doc/screenshot_1.png)

![](https://raw.github.com/willdurand/Propilex/master/doc/screenshot_2.png)


Unit Tests
----------

First, install the application as described in section [Installation](#installation).

### Backend

Install dev depedencies:

    php composer.phar install --dev

Then run the testsuite:

    bin/phpunit


### Frontend

In a browser, open `/js/tests/index.html`.

In a shell, install [PhantomJS](http://phantomjs.org/), and run the following comand:

    phantomjs web/js/tests/run-qunit.js file://`pwd`/web/js/tests/index.html


License
-------

Propilex is released under the MIT License. See the bundled LICENSE file for details.
