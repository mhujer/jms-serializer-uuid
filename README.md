# UUID support for JMS Serializer

[![Latest Stable Version](https://poser.pugx.org/mhujer/jms-serializer-uuid/version.png)](https://packagist.org/packages/mhujer/jms-serializer-uuid) [![Total Downloads](https://poser.pugx.org/mhujer/jms-serializer-uuid/downloads.png)](https://packagist.org/packages/mhujer/jms-serializer-uuid) [![License](https://poser.pugx.org/mhujer/jms-serializer-uuid/license.svg)](https://packagist.org/packages/mhujer/jms-serializer-uuid) [![Coverage Status](https://coveralls.io/repos/github/mhujer/jms-serializer-uuid/badge.svg?branch=master)](https://coveralls.io/github/mhujer/jms-serializer-uuid?branch=master)

This library allows you to serialize and deserialize [ramsey/uuid](https://github.com/ramsey/uuid) UUIDs
when using [JMS Serializer library](https://github.com/schmittjoh/serializer).

Usage
----
1. Install the latest version with `composer require mhujer/jms-serializer-uuid`
2. Register a custom handler to JMS Serializer ([documentation](https://jmsyst.com/libs/serializer/master/handlers))

```php
<?php
$builder
    ->configureHandlers(function(JMS\Serializer\Handler\HandlerRegistry $registry) {
        $registry->registerSubscribingHandler(new \Mhujer\JmsSerializer\Uuid\UuidSerializerHandler());
    })
;

```

or if you are using Symfony, register it as a tagged service in `services.yaml`: 

```yml

Mhujer\JmsSerializer\Uuid\UuidSerializerHandler:
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
Works with PHP 8.0 or higher.

Submitting bugs and feature requests
------------------------------------
Bugs and feature request are tracked on [GitHub](https://github.com/mhujer/jms-serializer-uuid/issues)

Author
------
[Martin Hujer](https://www.martinhujer.cz) 

Changelog
----------

## 3.4.1 (2021-12-07)
- [#17](../../pull/17) handle non-stringable values
- 
## 3.4.0 (2021-11-05)
- [#15](../../pull/15) Drop support for PHP < 8.0
- [#16](../../pull/16) Add support for PHP 8.1

## 3.3.0 (2020-12-31)
- [#12](../../pull/12) Drop support for PHP < 7.4
- [#11](../../pull/11) Add support for PHP 8.0 (thank you @ahilles107!)

## 3.2.0 (2020-05-17)
- [#10](../../pull/10) Add support for ramsey/uuid v4 (thank you @simPod!)

## 3.1.0 (2019-05-31)
- [#8](../../pull/8) [#9](../../pull/9) Add `jms/serializer` 3.0 support (thank you @ilyashtrikul and @simPod!)

## 3.0.0 (2018-11-27)
- [#6](../../pull/6) dropped support for PHP 7.1 as it is no longer supported
- [#7](../../pull/7) Require `jms/serializer` 2.0

## 2.0.0 (2018-01-05)
- [#3](../../pull/3) require PHP 7.1, potential BC breaks because of added type-hints

## 1.0.1 (2016-08-22)
- [#1](../../pull/1) fixed serialization to XML

## 1.0.0 (2016-05-28)
- initial release
