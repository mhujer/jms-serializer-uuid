<?php

declare(strict_types = 1);

namespace Mhujer\JmsSerializer\Uuid;

class NonStringCastableTypeException
	extends \Exception
	implements \Mhujer\JmsSerializer\Uuid\Exception
{

	/**
	 * @param mixed $value
	 * @param \Throwable|null $exception
	 */
	public function __construct($value, ?\Throwable $exception = null) // phpcs:ignore SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	{
		parent::__construct(
			sprintf(
				'Cannot convert value of type "%s" to string',
				gettype($value),
			),
			0,
			$exception
		);
	}

}
