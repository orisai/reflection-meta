<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder\UniquePropertiesTraits;

trait A1
{

	public string $a;

}

trait B1
{

	public string $b;

}

class UniquePropertiesTraitsClass
{

	use A1;
	use B1;

}
