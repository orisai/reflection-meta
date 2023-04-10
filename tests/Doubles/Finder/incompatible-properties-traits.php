<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraits;

use Bar;
use Foo;

trait A1
{

	public string $a = 'a';

	/**
	 * Both annotated
	 */
	public string $b;

	public string $c;

	#[Foo]
	#[Bar]
	public string $d;

	#[Foo]
	public string $e;

	#[Foo]
	public string $f;

}

trait A2
{

	use A1;

	/**
	 * Differs from A1
	 */
	public string $a = 'a';

}

trait B1
{

	/**
	 * Differs from A1 and A2
	 */
	public string $a = 'a';

	/**
	 * Differs from A1
	 */
	public string $b;

	#[Foo]
	public string $c;

	#[Bar]
	#[Foo]
	public string $d;

	#[Foo('a')]
	public string $e;

	#[Foo]
	#[Bar]
	public string $f;

}

class IncompatiblePropertiesTraitsClass
{

	use A2;
	use B1;

}
