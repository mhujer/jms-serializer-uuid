<?php

declare(strict_types = 1);

namespace Mhujer\JmsSerializer\Uuid;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\VisitorInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidSerializerHandler implements \JMS\Serializer\Handler\SubscribingHandlerInterface
{

	private const TYPE_UUID = 'uuid';

	/**
	 * @return string[][]
	 */
	public static function getSubscribingMethods(): array
	{
		$formats = [
			'json',
			'xml',
			'yml',
		];
		$methods = [];
		foreach ($formats as $format) {
			$methods[] = [
				'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
				'type' => self::TYPE_UUID,
				'format' => $format,
				'method' => 'serializeUuid',
			];
			$methods[] = [
				'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
				'type' => self::TYPE_UUID,
				'format' => $format,
				'method' => 'deserializeUuid',
			];
		}

		return $methods;
	}

	/**
	 * @param \JMS\Serializer\VisitorInterface $visitor
	 * @param mixed $data
	 * @param mixed[] $type
	 * @param \JMS\Serializer\Context $context
	 * @return \Ramsey\Uuid\UuidInterface
	 */
	public function deserializeUuid(VisitorInterface $visitor, $data, array $type, Context $context): UuidInterface
	{
		try {
			return $this->deserializeUuidValue((string) $data);
		} catch (\Mhujer\JmsSerializer\Uuid\InvalidUuidException $e) {
			throw new \Mhujer\JmsSerializer\Uuid\DeserializationInvalidValueException(
				$context->getCurrentPath()[0],
				$e
			);
		}
	}

	private function deserializeUuidValue(string $uuidString): UuidInterface
	{
		if (!Uuid::isValid($uuidString)) {
			throw new \Mhujer\JmsSerializer\Uuid\InvalidUuidException($uuidString);
		}
		return Uuid::fromString($uuidString);
	}

	/**
	 * @param \JMS\Serializer\VisitorInterface $visitor
	 * @param \Ramsey\Uuid\UuidInterface $uuid
	 * @param mixed[] $type
	 * @param \JMS\Serializer\Context $context
	 * @return string|object
	 */
	public function serializeUuid(VisitorInterface $visitor, UuidInterface $uuid, array $type, Context $context)
	{
		return $visitor->visitString($uuid->toString(), $type, $context);
	}

}
