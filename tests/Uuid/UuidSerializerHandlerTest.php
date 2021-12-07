<?php

declare(strict_types = 1);

namespace Mhujer\JmsSerializer\Uuid;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

class UuidSerializerHandlerTest extends \PHPUnit\Framework\TestCase
{

	public function testSerializeUuidToJson(): void
	{
		$user = new User();
		$user->id = Uuid::fromString('86be949f-7f46-4457-9230-fad9783337aa');

		$serializer = $this->getSerializer();
		$json = $serializer->serialize($user, 'json');

		$this->assertSame('{"id":"86be949f-7f46-4457-9230-fad9783337aa"}', $json);
	}

	public function testSerializeUuidToXml(): void
	{
		$user = new User();
		$user->id = Uuid::fromString('86be949f-7f46-4457-9230-fad9783337aa');

		$serializer = $this->getSerializer();
		$json = $serializer->serialize($user, 'xml');

		$this->assertSame(
			'<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
			'<result>' . "\n" .
			'  <id><![CDATA[86be949f-7f46-4457-9230-fad9783337aa]]></id>' . "\n" .
			'</result>' . "\n",
			$json
		);
	}

	public function testDeserializeUuidFromJson(): void
	{
		$expectedUuid = Uuid::fromString('86be949f-7f46-4457-9230-fad9783337aa');

		$serializer = $this->getSerializer();
		/** @var \Mhujer\JmsSerializer\Uuid\User $user */
		$user = $serializer->deserialize('{
			"id":"86be949f-7f46-4457-9230-fad9783337aa"
		}', User::class, 'json');

		$this->assertInstanceOf(User::class, $user);
		$this->assertTrue($user->id->equals($expectedUuid));
	}

	public function testDeserializeUuidFromXml(): void
	{
		$expectedUuid = Uuid::fromString('86be949f-7f46-4457-9230-fad9783337aa');

		$serializer = $this->getSerializer();
		/** @var \Mhujer\JmsSerializer\Uuid\User $user */
		$user = $serializer->deserialize(
			'<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
			'<result>' . "\n" .
			'  <id><![CDATA[86be949f-7f46-4457-9230-fad9783337aa]]></id>' . "\n" .
			'</result>' . "\n",
			User::class,
			'xml'
		);

		$this->assertInstanceOf(User::class, $user);
		$this->assertTrue($user->id->equals($expectedUuid));
	}

	public function testDeserializeInvalidUuid(): void
	{
		$serializer = $this->getSerializer();

		try {
			$serializer->deserialize('{
				"id":"86be949f-7f46-4457-9230-fad9783337xx"
			}', User::class, 'json');

			$this->fail();

		} catch (\Mhujer\JmsSerializer\Uuid\DeserializationInvalidValueException $e) {
			$this->assertSame('id', $e->getFieldPath());
			/** @var \Mhujer\JmsSerializer\Uuid\InvalidUuidException $previous */
			$previous = $e->getPrevious();
			$this->assertSame('86be949f-7f46-4457-9230-fad9783337xx', $previous->getInvalidUuid());
		}
	}

	public function testDeserializeWithArrayInsteadOfIdThrows(): void
	{
		$serializer = $this->getSerializer();

		$this->expectException(\Mhujer\JmsSerializer\Uuid\NonStringCastableTypeException::class);
		$this->expectExceptionMessage('Cannot convert value of type "array" to string');

		$serializer->deserialize('{
			"id":{}
		}', User::class, 'json');
	}

	private function getSerializer(): SerializerInterface
	{
		return SerializerBuilder::create()
			->configureHandlers(function (HandlerRegistry $registry): void {
				$registry->registerSubscribingHandler(new UuidSerializerHandler());
			})
			->build();
	}

}
