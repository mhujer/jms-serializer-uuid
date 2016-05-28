<?php

namespace Mhujer\JmsSerializer\Uuid;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;

use Ramsey\Uuid\Uuid;

class UuidSerializerHandlerTest extends \PHPUnit_Framework_TestCase
{

	public function testSerializeUuid()
	{
		$user = new User();
		$user->id = Uuid::fromString('86be949f-7f46-4457-9230-fad9783337aa');

		$serializer = $this->getSerializer();
		$json = $serializer->serialize($user, 'json');

		$this->assertSame('{"id":"86be949f-7f46-4457-9230-fad9783337aa"}', $json);
	}

	public function testDeserializeUuid()
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

	public function testDeserializeInvalidUuid()
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

	/**
	 * @return \JMS\Serializer\Serializer
	 */
	private function getSerializer()
	{
		return SerializerBuilder::create()
			->configureHandlers(function (HandlerRegistry $registry) {
				$registry->registerSubscribingHandler(new UuidSerializerHandler());
			})
			->build();
	}

}
