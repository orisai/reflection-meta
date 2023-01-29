<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Classes;

use stdClass;

interface ClassStructureDoubleInterface1
{

}

trait ClassStructureDoubleTrait1
{

}

final class ClassStructureDouble1 extends stdClass implements ClassStructureDoubleInterface1
{

	use ClassStructureDoubleTrait1;

	public const A = 'a';

	public string $b = 'b';

	public function c(): void
	{
		// Noop
	}

}
