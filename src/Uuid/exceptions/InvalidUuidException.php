<?php

declare(strict_types = 1);

namespace Mhujer\JmsSerializer\Uuid;

class InvalidUuidException
	extends \Exception
	implements \Mhujer\JmsSerializer\Uuid\Exception
{

	/** @var string */
	private $invalidUuid;

	public function __construct(string $invalidUuid, ?\Throwable $exception = null)
	{
		parent::__construct(
			sprintf('"%s" is not a valid UUID', $invalidUuid),
			0,
			$exception
		);
		$this->invalidUuid = $invalidUuid;
	}

	public function getInvalidUuid(): string
	{
		return $this->invalidUuid;
	}

}
