# UUID support for JMS Serializer [![Build Status](https://travis-ci.org/mhujer/jms-serializer-uuid.svg?branch=master)](https://travis-ci.org/mhujer/jms-serializer-uuid)

[![Latest Stable Version](https://poser.pugx.org/mhujer/jms-serializer-uuid/version.png)](https://packagist.org/packages/mhujer/jms-serializer-uuid) [![Total Downloads](https://poser.pugx.org/mhujer/jms-serializer-uuid/downloads.png)](https://packagist.org/packages/mhujer/jms-serializer-uuid) [![License](https://poser.pugx.org/mhujer/jms-serializer-uuid/license.svg)](https://packagist.org/packages/mhujer/jms-serializer-uuid) [![Coverage Status](https://coveralls.io/repos/mhujer/jms-serializer-uuid/badge.svg?branch=master)](https://coveralls.io/r/mhujer/jms-serializer-uuid?branch=master)

This library allows you to serialize and deserialize [ramsey/uuid](https://github.com/ramsey/uuid) UUIDs
when using [JMS Serializer library](https://github.com/schmittjoh/serializer).

[There is a Symfony Bundle](https://github.com/mhujer/jms-serializer-uuid-bundle) for using this library in a Symfony application.

Usage
----
1. Install the latest version with `composer require mhujer/jms-serializer-uuid`
2. Register a custom handler to JMS Serializer ([documentation](http://jmsyst.com/libs/serializer/master/handlers))

```php
<?php
$builder
    ->configureHandlers(function(JMS\Serializer\Handler\HandlerRegistry $registry) {
        $registry->registerSubscribingHandler(new \Mhujer\JmsSerializer\Uuid\UuidSerializerHandler());
    })
;

```

or if you are using Symfony, register it as a tagged service:

```yml

mhujer.jms_serializer.uuid.uuid_serializer_handler:
    class: Mhujer\JmsSerializer\Uuid\UuidSerializerHandler
    tags:
        - { name: jms_serializer.subscribing_handler }

```

Then you can use the `uuid` type for serialization or deserialization:

```php
<?php

use JMS\Serializer\Annotation as JMS;

class User
{

	/**
	 * @JMS\Type("uuid")
	 * @var \Ramsey\Uuid\UuidInterface
	 */
	public $id;

}
```

Requirements
------------
Works with PHP 7.2 or higher.

Submitting bugs and feature requests
------------------------------------
Bugs and feature request are tracked on [GitHub](https://github.com/mhujer/jms-serializer-uuid/issues)

Author
------
[Martin Hujer](https://www.martinhujer.cz) 

Changelog
----------

## 3.0.0 (2018-11-27)
- [#6](../../pull/6) dropped support for PHP 7.1 as it is no longer supported
- [#7](../../pull/7) Require `jms/serializer` 2.0

## 2.0.0 (2018-01-05)
- [#3](../../pull/3) require PHP 7.1, potential BC breaks because of added type-hints

## 1.0.1 (2016-08-22)
- [#1](../../pull/1) fixed serialization to XML

## 1.0.0 (2016-05-28)
- initial release
