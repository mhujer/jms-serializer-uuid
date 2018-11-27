<?php

declare(strict_types = 1);

namespace Mhujer\JmsSerializer\Uuid;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidSerializerHandler implements \JMS\Serializer\Handler\SubscribingHandlerInterface
{

	private const PATH_FIELD_SEPARATOR = '.';

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
				'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
				'type' => self::TYPE_UUID,
				'format' => $format,
				'method' => 'serializeUuid',
			];
			$methods[] = [
				'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
				'type' => self::TYPE_UUID,
				'format' => $format,
				'method' => 'deserializeUuid',
			];
		}

		return $methods;
	}

	/**
	 * @param \JMS\Serializer\Visitor\DeserializationVisitorInterface $visitor
	 * @param mixed $data
	 * @param mixed[] $type
	 * @param \JMS\Serializer\Context $context
	 * @return \Ramsey\Uuid\UuidInterface
	 */
	public function deserializeUuid(DeserializationVisitorInterface $visitor, $data, array $type, Context $context): UuidInterface
	{
		try {
			return $this->deserializeUuidValue((string) $data);
		} catch (\Mhujer\JmsSerializer\Uuid\InvalidUuidException $e) {
			throw new \Mhujer\JmsSerializer\Uuid\DeserializationInvalidValueException(
				$this->getFieldPath($context),
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
	 * @param \JMS\Serializer\Visitor\SerializationVisitorInterface $visitor
	 * @param \Ramsey\Uuid\UuidInterface $uuid
	 * @param mixed[] $type
	 * @return string|object
	 */
	public function serializeUuid(SerializationVisitorInterface $visitor, UuidInterface $uuid, array $type)
	{
		return $visitor->visitString($uuid->toString(), $type);
	}

	private function getFieldPath(Context $context): string
	{
		$path = '';
		foreach ($context->getMetadataStack() as $element) {
			if ($element instanceof PropertyMetadata) {
				$name = ($element->serializedName !== null) ? $element->serializedName : $element->name;

				$path = $name . self::PATH_FIELD_SEPARATOR . $path;
			}
		}
		$path = rtrim($path, self::PATH_FIELD_SEPARATOR);

		return $path;
	}

}
