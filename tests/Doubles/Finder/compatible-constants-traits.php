<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatibleConstantsTraits;

use Foo;
use stdClass;

trait A1
{

	public const a = 'a';

	/**
	 * All annotated
	 */
	public const b = 'b';

	#[Foo(bar: new stdClass())]
	public const c = 'c';

}

trait A2
{

	use A1;

	public const a = 'a';

	/**
	 * All annotated
	 */
	public const b = 'b';

}

trait B1
{

	public const a = 'a';

	/**
	 * All annotated
	 */
	public const b = 'b';

	#[Foo(bar: new stdClass())]
	public const c = 'c';

}

class CompatibleConstantsTraitsClass
{

	use A2;
	use B1;

	public const d = 'd';

}
