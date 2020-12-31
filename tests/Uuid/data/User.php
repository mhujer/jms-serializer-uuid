<?php

declare(strict_types = 1);

namespace Mhujer\JmsSerializer\Uuid;

use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\UuidInterface;

class User
{

	/** @JMS\Type("uuid") */
	public UuidInterface $id;

}
