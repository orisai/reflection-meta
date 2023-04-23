<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatibleConstantsTraits;

use Bar;
use Foo;
use Tests\Orisai\ReflectionMeta\Doubles\DataCrate;

trait A1
{

	public const a = 'a';

	/**
	 * Both annotated
	 */
	public const b = 'b';

	public const c = 'c';

	#[Foo]
	#[Bar]
	public const d = 'd';

	#[Foo]
	public const e = 'e';

	#[Foo]
	public const f = 'f';

	#[Foo(bar: new DataCrate('a1'))]
	public const g = 'g';

}

trait A2
{

	use A1;

	/**
	 * Differs from A1
	 */
	public const a = 'a';

	#[Foo]
	public const c = 'c';

	#[Bar]
	#[Foo]
	public const d = 'd';

	#[Foo('a')]
	public const e = 'e';

	#[Foo]
	#[Bar]
	public const f = 'f';

	#[Foo(bar: new DataCrate('a2'))]
	public const g = 'g';

}

trait B1
{

	/**
	 * Differs from A1 and A2
	 */
	public const a = 'a';

	/**
	 * Differs from A1
	 */
	public const b = 'b';

	#[Foo]
	public const c = 'c';

	#[Bar]
	#[Foo]
	public const d = 'd';

	#[Foo('a')]
	public const e = 'e';

	#[Foo]
	#[Bar]
	public const f = 'f';

	#[Foo(bar: new DataCrate('b1'))]
	public const g = 'g';

}

class IncompatibleConstantsTraitsClass
{

	use A2;
	use B1;

}
