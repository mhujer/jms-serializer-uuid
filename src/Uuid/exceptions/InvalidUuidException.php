<?php

namespace Mhujer\JmsSerializer\Uuid;

class InvalidUuidException
	extends \Exception
	implements \Mhujer\JmsSerializer\Uuid\Exception
{

	/** @var string */
	private $invalidUuid;

	/**
	 * @param string $invalidUuid
	 * @param \Exception|null $exception
	 */
	public function __construct($invalidUuid, \Exception $exception = null)
	{
		parent::__construct(
			sprintf('"%s" is not a valid UUID', $invalidUuid),
			$exception
		);
		$this->invalidUuid = $invalidUuid;
	}

	/**
	 * @return string
	 */
	public function getInvalidUuid()
	{
		return $this->invalidUuid;
	}

}
