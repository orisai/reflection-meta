<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatibleConstantsTraits;

use Bar;
use Foo;

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

}

trait A2
{

	use A1;

	/**
	 * Differs from A1
	 */
	public const a = 'a';

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

}

class IncompatibleConstantsTraitsClass
{

	use A2;
	use B1;

}
