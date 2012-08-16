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


Todos
-------

* Log user activities and send admin emails
* Enable to update affiliation
* Enable to specify user meals
* Enable to specify night location
* Enable to specify user meal preparation
* Add contact and location pages
* Add login and user authentication (with cookie `remember me`)
* Restrict user update to their own datas and place own information on top of the list
* Add user table view
* Add meal preparation table view
* Add night location table view
* Update styles
* Enhance responsive styles
* Add images gallery
* Add affiliation user tag filter
* Use Assetics
* Install on prod server
* Create cron task on prod server to update every night ?