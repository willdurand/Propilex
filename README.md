Propilex
========

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

    bin/bootstrap


Configure a database:

    mysql -uroot -e 'CREATE DATABASE propilex'

    mysql -uroot propilex < app/config/sql/Propilex.Model.schema.sql


You're done! You can run the application using the PHP built-in webserver:

    php -S 0.0.0.0:4000 -t web/

Open `http://localhost:4000/` in your browser to see the Propilex running.


Configuration
-------------

All the configuration is located in the `app/config/` directory.

* `runtime-conf.xml` contains the database configuration, if you modify it, don't forget to rebuild things by using the previous command;
* `config.php` you should **not** edit this file, except to turn on/off debugging stuffs.


Screenshots
-----------

![](https://raw.github.com/willdurand/Propilex/master/doc/screenshot_1.png)

![](https://raw.github.com/willdurand/Propilex/master/doc/screenshot_2.png)


License
-------

Propilex is released under the MIT License. See the bundled LICENSE file for details.
