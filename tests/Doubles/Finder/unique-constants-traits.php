<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder\UniqueConstantsTraits;

trait A1
{

	public const a = 'a';

}

trait B1
{

	public const b = 'b';

}

class UniqueConstantsTraitsClass
{

	use A1;
	use B1;

}
