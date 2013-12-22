Propilex
========

[![Build
Status](https://secure.travis-ci.org/willdurand/Propilex.png?branch=master)](https://travis-ci.org/willdurand/Propilex)

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

    composer install


And browser dependencies using [Bower](http://twitter.github.com/bower/):

    cd web && bower install
    cd ..


Build Model classes, SQL, and the configuration:

    cp app/config/propel/runtime-conf.xml.dist app/config/propel/runtime-conf.xml
    cp app/config/propel/build.properties.dist app/config/propel/build.properties
    bin/bootstrap


You're done! You can run the application using the PHP built-in webserver:

    php -S 0.0.0.0:4000 -t web/

Open `http://localhost:4000/` in your browser to see Propilex running.


Usage
-----

You can use the web interface, or the command line and tools surch as
[HTTPie](https://github.com/jkbr/httpie) or [cURL](http://curl.haxx.se/):

### GET

Getting all documents in JSON:

    $ http http://localhost:4000/documents Accept:application/json
    HTTP/1.1 200 OK
    Content-Type: application/json

    {
        "_links": {
            "first": {
                "href": "http://localhost:4000/documents?page=1&limit=10"
            },
            "last": {
                "href": "http://localhost:4000/documents?page=1&limit=10"
            },
            "self": {
                "href": "http://localhost:4000/documents?page=1&limit=10"
            }
        },
        "documents": [
            {
                "_links": {
                    "self": {
                        "href": "http://localhost:4000/documents/1"
                    }
                },
                "body": "Hello, World!",
                "created_at": "2013-12-22 17:55:18",
                "id": 1,
                "title": "Hello!",
                "updated_at": "2013-12-22 17:55:18"
            },
            {
                "_links": {
                    "self": {
                        "href": "http://localhost:4000/documents/2"
                    }
                },
                "body": "This is a body",
                "created_at": "2013-12-22 17:55:22",
                "id": 2,
                "title": "This is a title",
                "updated_at": "2013-12-22 22:09:37"
            }
        ],
        "limit": 10,
        "page": 1,
        "pages": 1
    }

Getting all documents in XML:

    $ http http://localhost:4000/documents Accept:application/xml
    HTTP/1.1 200 OK
    Content-Type: application/xml

    <?xml version="1.0" encoding="UTF-8"?>
    <collection limit="10" page="1" pages="1">
        <documents>
            <document>
                <id>1</id>
                <title>Hello!</title>
                <body>Hello, World!</body>
                <created_at><![CDATA[2013-12-22 17:55:18]]></created_at>
                <updated_at><![CDATA[2013-12-22 17:55:18]]></updated_at>
                <link href="http://localhost:4000/documents/1" rel="self"></link>
            </document>
            <document>
                <id>2</id>
                <title>This is a title</title>
                <body>This is a body</body>
                <created_at><![CDATA[2013-12-22 17:55:22]]></created_at>
                <updated_at><![CDATA[2013-12-22 22:09:37]]></updated_at>
                <link href="http://localhost:4000/documents/2" rel="self"></link>
            </document>
        </documents>
        <link href="http://localhost:4000/documents?page=1&limit=10" rel="self"></link>
        <link href="http://localhost:4000/documents?page=1&limit=10" rel="first"></link>
        <link href="http://localhost:4000/documents?page=1&limit=10" rel="last"></link>
    </collection>

Getting a single document in JSON:

    $ http http://localhost:4000/documents/1 Accept:application/json
    HTTP/1.1 200 OK
    Cache-Control: private, must-revalidate
    Content-Type: application/json
    Last-Modified: Sun, 22 Dec 2013 21:41:55 GMT

    {
        "_links": {
            "self": {
                "href": "http://localhost:4000/documents/1"
            }
        },
        "body": "Hello, World!",
        "created_at": "2013-12-22 17:55:18",
        "id": 1,
        "title": "Hello!",
        "updated_at": "2013-12-22 22:41:55"
    }

Getting a single document in XML:

    $ http http://localhost:4000/documents/1 Accept:application/xml
    HTTP/1.1 200 OK
    Cache-Control: private, must-revalidate
    Content-Type: application/xml
    Last-Modified: Sun, 22 Dec 2013 21:41:55 GMT

    <?xml version="1.0" ?>
    <document>
        <id>1</id>
        <title><![CDATA[Hello!]]></title>
        <body><![CDATA[Hello, World!]]></body>
        <created_at><![CDATA[2013-12-22 17:55:18]]></created_at>
        <updated_at><![CDATA[2013-12-22 22:41:55]]></updated_at>
        <link href="http://localhost:4000/documents/1" rel="self"/>
    </document>

### POST

Creating a new document by sending JSON data:

    $ curl -H 'Accept: application/json' -H 'Content-Type: application/json' \
        -d '{"title": "Hello!", "body": "JSON"}' \
        http://localhost:4000/documents

    HTTP/1.1 200 OK
    Content-Type: application/json
    Location: http://localhost:4000/documents/7"

    {
        "id": 7,
        "title": "Hello!",
        "body": "JSON",
        "created_at": "2013-12-22 22:48:46",
        "updated_at": "2013-12-22 22:48:46",
        "_links": {
            "self": {
                "href": "http://localhost:4000/documents/7"
            }
        }
    }

Creating a new document by sending XML data:

    $ curl -H 'Accept: application/json' -H 'Content-Type: application/xml' \
        -d '<document><title>Hello!</title><body>XML</body></document>' \
        http://localhost:4000/documents

    HTTP/1.1 200 OK
    Content-Type: application/json
    Location: http://localhost:4000/documents/8"

    {
        "id": 8,
        "title": "Hello!",
        "body": "XML",
        "created_at": "2013-12-22 22:50:46",
        "updated_at": "2013-12-22 22:50:46",
        "_links": {
            "self": {
                "href": "http://localhost:4000/documents/8"
            }
        }
    }

XML response for a validation error:

    $ curl -H 'Accept: application/xml' -H 'Content-Type: application/json' \
        -d '{"title": "Hello!"}' \
        http://localhost:4000/documents

    HTTP/1.1 400 Bad Request
    Content-Type: application/xml

    <?xml version="1.0" encoding="UTF-8"?>
    <errors>
        <error field="body">
            <message><![CDATA[This value should not be blank.]]></message>
        </error>
    </errors>

JSON response for a validation error:

    $ curl -H 'Accept: application/json' -H 'Content-Type: application/json' \
        -d '{"title": "Hello!"}' \
        http://localhost:4000/documents

    HTTP/1.1 400 Bad Request
    Content-Type: application/json

    {
        "errors": [
            {
                "field": "body",
                "message": "This value should not be blank."
            }
        ]
    }

### DELETE

    $ http DELETE http://localhost:4000/documents/1
    HTTP/1.1 204 No Content

JSON response for an error:

    $ http DELETE http://localhost:4000/documents/70 Accept:application/json
    HTTP/1.1 404 Not Found

    {
        "message": "Document with id = 7 does not exist."
    }

XML response for an error:

    $ http DELETE http://localhost:4000/documents/70 Accept:application/xml
    HTTP/1.1 404 Not Found

    <?xml version="1.0" ?>
    <error>
        <message><![CDATA[Document with id = 70 does not exist.]]></message>
    </error>


Configuration
-------------

All configuration files is located in the `app/config/` directory.

* `propel/runtime-conf.xml` and `propel/build.properties` contain the database
  configuration, if you modify it, don't forget to rebuild things by using the
  previous command;
* `serializer/*` contains the Serializer and Hateoas configuration;
* `config.php` you should **not** edit this file, except to turn on/off
  debugging stuff;
* `validation.yml` contains the Validation configuration.


Screenshots
-----------

![](https://raw.github.com/willdurand/Propilex/master/doc/screenshot_1.png)

![](https://raw.github.com/willdurand/Propilex/master/doc/screenshot_2.png)


Unit Tests
----------

First, install the application as described in section
[Installation](#installation).

### Backend

Install dev depedencies:

    composer install --dev

Then, run the test suite:

    bin/phpunit


### Frontend

In a browser, open `/js/tests/index.html`.

In a shell, install [PhantomJS](http://phantomjs.org/), and run the following
comand:

    phantomjs web/js/tests/run-qunit.js file://`pwd`/web/js/tests/index.html


License
-------

Propilex is released under the MIT License. See the bundled LICENSE file for
details.
