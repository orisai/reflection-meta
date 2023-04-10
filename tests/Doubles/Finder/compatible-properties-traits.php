<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatiblePropertiesTraits;

trait A1
{

	public string $a = 'a';

	/**
	 * All annotated
	 */
	public string $b;

	#[\Foo('bar')]
	public string $c;

}

trait A2
{

	use A1;

	public string $a = 'a';

	/**
	 * All annotated
	 */
	public string $b;

}

trait B1
{

	public string $a = 'a';

	/**
	 * All annotated
	 */
	public string $b;

	#[\Foo('bar')]
	public string $c;

}

class CompatiblePropertiesTraitsClass
{

	use A2, B1;

	public string $d;

}
