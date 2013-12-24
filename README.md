Propilex
========

[![Build
Status](https://secure.travis-ci.org/willdurand/Propilex.png?branch=master)](https://travis-ci.org/willdurand/Propilex)

A [Silex](http://silex.sensiolabs.org) application which uses
[Propel](http://propelorm.org),
[Backbone.JS](http://backbonejs.org/), but also:

* [Bower](http://bower.io/) as browser package manager;
* [RequireJS](http://requirejs.org/);
* [Garlic.js](http://garlicjs.org);
* [Moment.js](http://momentjs.com/);
* [Twitter Bootstrap](http://twitter.github.com/bootstrap/);
* [Less CSS](http://lesscss.org/);
* [Backbone.Forms](https://github.com/powmedia/backbone-forms);
* [Keymaster](https://github.com/madrobby/keymaster).

And:

* [Hateoas](https://github.com/willdurand/Hateoas);
* [Negotiation](https://github.com/willdurand/Negotiation);
* [StackNegotiation](https://github.com/willdurand/StackNegotiation);
* [Stack](http://stackphp.com);
* [TemplatedUriRouter](https://github.com/hautelook/TemplatedUriRouter).


Installation
------------

Install PHP dependencies:

    composer install


And browser dependencies using [Bower](http://bower.io/):

    bower install

Build Model classes, SQL, and Propel's configuration:

    cp app/config/propel/runtime-conf.xml.dist app/config/propel/runtime-conf.xml
    cp app/config/propel/build.properties.dist app/config/propel/build.properties
    bin/bootstrap


You're done! You can run the application using the PHP built-in webserver:

    php -S 0.0.0.0:4000 -t web/

Open `http://localhost:4000/` in your browser to see Propilex running.


Usage
-----

This application is a **truely RESTful API** with hypermedia links, content
negotiation (format and language) but also cache on safe methods. The API is
[HAL](http://stateless.co/hal_specification.html) compliant, and serves content
in either XML or JSON format, using the most appropriate language (English,
French, etc.) based on clients' preferences.

You can use the web interface, or the command line and tools such as
[HTTPie](https://github.com/jkbr/httpie) or [cURL](http://curl.haxx.se/).

### GET

You can get either a set of documents, or a single document. The responses for
these data are cacheable, using the `Last-Modified` and `ETag` headers.

Getting all documents in JSON:

    $ http http://localhost:4000/documents Accept:application/hal+json
    HTTP/1.1 200 OK
    Cache-Control: public
    Content-Type: application/hal+json
    ETag: "c4ca4238a0b923820dcc509a6f75849b"

```json
{
    "_links": {
        "curies": [
            {
                "href": "http://localhost:4000/rels/{rel}",
                "name": "p",
                "templated": true
            }
        ],
        "p:documents": {
            "href": "http://localhost:4000/documents"
        },
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
    "_embedded": {
        "documents": [
            {
                "_links": {
                    "self": {
                        "href": "http://localhost:4000/documents/1"
                    },
                    "curies": [
                        {
                            "href": "http://localhost:4000/rels/{rel}",
                            "name": "p",
                            "templated": true
                        }
                    ]
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
                    },
                    "curies": [
                        {
                            "href": "http://localhost:4000/rels/{rel}",
                            "name": "p",
                            "templated": true
                        }
                    ]
                },
                "body": "This is a body",
                "created_at": "2013-12-22 17:55:22",
                "id": 2,
                "title": "This is a title",
                "updated_at": "2013-12-22 22:09:37"
            }
        ]
    },
    "limit": 10,
    "page": 1,
    "pages": 1
}
```

Getting all documents in XML:

    $ http http://localhost:4000/documents Accept:application/hal+xml
    HTTP/1.1 200 OK
    Cache-Control: public
    Content-Type: application/hal+xml
    ETag: "c4ca4238a0b923820dcc509a6f75849b"

```xml
<?xml version="1.0" encoding="UTF-8"?>
<collection href="http://localhost:4000/documents?page=1&amp;limit=10" limit="10" page="1" pages="1">
    <resource href="http://localhost:4000/documents/1" rel="documents">
        <id>1</id>
        <title>Hello!</title>
        <body>Hello, World!</body>
        <created_at><![CDATA[2013-12-22 17:55:18]]></created_at>
        <updated_at><![CDATA[2013-12-22 17:55:18]]></updated_at>
        <link href="http://localhost:4000/rels/{rel}" name="p" rel="curies" templated="1"/>
    </resource>
    <resource href="http://localhost:4000/documents/2" rel="documents">
        <id>2</id>
        <title>This is a title</title>
        <body>This is a body</body>
        <created_at><![CDATA[2013-12-22 17:55:22]]></created_at>
        <updated_at><![CDATA[2013-12-22 22:09:37]]></updated_at>
        <link href="http://localhost:4000/rels/{rel}" name="p" rel="curies" templated="1"/>
    </resource>
    <link href="http://localhost:4000/documents?page=1&limit=10" rel="first"></link>
    <link href="http://localhost:4000/documents?page=1&limit=10" rel="last"></link>

    <link href="http://localhost:4000/rels/{rel}" name="p" rel="curies" templated="1"/>
    <link href="http://localhost:4000/documents" rel="p:documents"/>
</collection>
```

Getting a single document in JSON:

    $ http http://localhost:4000/documents/1 Accept:application/hal+json
    HTTP/1.1 200 OK
    Cache-Control: public
    Content-Type: application/hal+json
    Last-Modified: Sun, 22 Dec 2013 21:41:55 GMT

```json
{
    "_links": {
        "self": {
            "href": "http://localhost:4000/documents/1"
        },
        "curies": [
            {
                "href": "http://localhost:4000/rels/{rel}",
                "name": "p",
                "templated": true
            }
        ]
    },
    "body": "Hello, World!",
    "created_at": "2013-12-22 17:55:18",
    "id": 1,
    "title": "Hello!",
    "updated_at": "2013-12-22 22:41:55"
}
```

Getting a single document in XML:

    $ http http://localhost:4000/documents/1 Accept:application/hal+xml
    HTTP/1.1 200 OK
    Cache-Control: public
    Content-Type: application/hal+xml
    Last-Modified: Sun, 22 Dec 2013 21:41:55 GMT

```xml
<?xml version="1.0" ?>
<document href="http://localhost:4000/documents/1">
    <id>1</id>
    <title><![CDATA[Hello!]]></title>
    <body><![CDATA[Hello, World!]]></body>
    <created_at><![CDATA[2013-12-22 17:55:18]]></created_at>
    <updated_at><![CDATA[2013-12-22 22:41:55]]></updated_at>
    <link href="http://localhost:4000/rels/{rel}" name="p" rel="curies" templated="1"/>
</document>
```

### POST

You can create a new document by sending JSON data:

    $ curl -H 'Accept: application/hal+json' -H 'Content-Type: application/json' \
        -d '{"title": "Hello!", "body": "JSON"}' \
        http://localhost:4000/documents
    HTTP/1.1 201 Created
    Content-Type: application/hal+json
    Location: http://localhost:4000/documents/7"

```json
{
    "id": 7,
    "title": "Hello!",
    "body": "JSON",
    "created_at": "2013-12-22 22:48:46",
    "updated_at": "2013-12-22 22:48:46",
    "_links": {
        "self": {
            "href": "http://localhost:4000/documents/7"
        },
        "curies": [
            {
                "href": "http://localhost:4000/rels/{rel}",
                "name": "p",
                "templated": true
            }
        ]
    }
}
```

Creating a new document is also doable by sending XML data:

    $ curl -H 'Accept: application/hal+json' -H 'Content-Type: application/xml' \
        -d '<document><title>Hello!</title><body>XML</body></document>' \
        http://localhost:4000/documents
    HTTP/1.1 201 Created
    Content-Type: application/hal+json
    Location: http://localhost:4000/documents/8"

```json
{
    "id": 8,
    "title": "Hello!",
    "body": "XML",
    "created_at": "2013-12-22 22:50:46",
    "updated_at": "2013-12-22 22:50:46",
    "_links": {
        "self": {
            "href": "http://localhost:4000/documents/8"
        },
        "curies": [
            {
                "href": "http://localhost:4000/rels/{rel}",
                "name": "p",
                "templated": true
            }
        ]
    }
}
```

### DELETE

    $ http DELETE http://localhost:4000/documents/1
    HTTP/1.1 204 No Content

If the document you are trying to delete does not exist, you will get an error:

    $ http DELETE http://localhost:4000/documents/70 Accept:application/hal+json
    HTTP/1.1 404 Not Found
    Content-Type: application/vnd.error+json

```json
{
    "message": "Document with id = 70 does not exist."
}
```

XML response for this error:

    $ http DELETE http://localhost:4000/documents/70 Accept:application/hal+xml
    HTTP/1.1 404 Not Found
    Content-Type: application/vnd.error+xml

```xml
<?xml version="1.0" ?>
<resource>
    <message><![CDATA[Document with id = 70 does not exist.]]></message>
</resource>
```

### Translations & Error Messages

Both error messages or application's messages are translated depending on the
`Accept-Language` header. In order to implement this, you need to use the
[StackNegotiation](https//github.com/willdurand/StackNegotiation) middleware,
and a [Silex application's **before**
middleware](https://github.com/willdurand/Propilex/blob/master/app/config/config.php#L72-L89).

A response with a status code equals to either `404` or `500` follows the
[vnd.error](https://github.com/blongden/vnd.error) specification.

You will get an error message if you try to get an unknown document:

    $ http GET http://localhost:4000/documents/123 Accept:application/hal+json Accept-Language:en
    HTTP/1.1 404 Not Found
    Content-Type: application/vnd.error+json

```json
{
    "message": "Document with id = \"123\" does not exist."
}
```

XML response for this error:

    $ http GET http://localhost:4000/documents/123 Accept:application/hal+xml Accept-Language:fr
    HTTP/1.1 404 Not Found
    Content-Type: application/vnd.error+xml

```xml
<?xml version="1.0" ?>
<resource>
    <message><![CDATA[Le document avec id = "123" n'existe pas.]]></message>
</resource>
```

You will get an error message if you submit invalid data in order to create or
update documents:

    $ http POST http://localhost:4000/documents Accept:application/hal+json Accept-Language:fr
    HTTP/1.1 400 Bad Request
    Content-Type: application/json

```json
{
    "errors": [
        {
            "field": "title",
            "message": "Cette valeur ne doit pas être vide."
        },
        {
            "field": "body",
            "message": "Cette valeur ne doit pas être vide."
        }
    ]
}
```

XML response for this error:

    $ curl -H 'Accept: application/hal+xml' -H 'Content-Type: application/json' \
        -d '{"title": "Hello!"}' \
        http://localhost:4000/documents
    HTTP/1.1 400 Bad Request
    Content-Type: application/xml

```xml
<?xml version="1.0" encoding="UTF-8"?>
<errors>
    <error field="body">
        <message><![CDATA[This value should not be blank.]]></message>
    </error>
</errors>
```

If you send an `Accept` header with an unsupported mime type, you will get a
`406` error:

    $ http GET http://propilex.herokuapp.com/documents Accept:application/json
    HTTP/1.1 406 Not Acceptable
    Content-Type: text/plain

    Mime type "application/json" is not supported. Supported mime types are:
    application/hal+xml, application/hal+json.



Configuration
-------------

All configuration files are located in the `app/config/` directory.

* `propel/runtime-conf.xml` and `propel/build.properties` contain the database
  configuration, if you modify it, don't forget to rebuild things by using the
  previous command;
* `serializer/*` contains the Serializer and Hateoas configuration;
* `messages.*.yml` contain the translations;
* `validation.yml` contains the Validation configuration.

You can also find a few parameters in `app/propilex.php`.


Screenshots
-----------

![](https://raw.github.com/willdurand/Propilex/master/doc/screenshot_1.png)

![](https://raw.github.com/willdurand/Propilex/master/doc/screenshot_2.png)


Deploy on Heroku
----------------

Create a new Heroku application:

    heroku create --buildpack https://github.com/CHH/heroku-buildpack-php myapp

Deploy it!

    git push heroku master


Unit Tests
----------

First, install the application as described in section
[Installation](#installation).

### Backend

Install dev dependencies:

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
