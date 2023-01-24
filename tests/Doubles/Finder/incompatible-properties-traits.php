<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraits;

trait A1
{

	public string $a = 'a';

	/**
	 * Both annotated
	 */
	public string $b;

	public string $c;

	#[\Foo]
	#[\Bar]
	public string $d;

	#[\Foo]
	public string $e;

	#[\Foo]
	public string $f;

}

trait A2
{

	use A1;

}

trait B1
{

	/**
	 * Just an annotation to trigger compatibility check
	 */
	public string $a = 'a';

	/**
	 * Both annotated, but differently
	 */
	public string $b;

	#[\Foo]
	public string $c;

	#[\Bar]
	#[\Foo]
	public string $d;

	#[\Foo('a')]
	public string $e;

	#[\Foo]
	#[\Bar]
	public string $f;

}

class IncompatiblePropertiesTraitsClass
{

	use A2, B1;
}
