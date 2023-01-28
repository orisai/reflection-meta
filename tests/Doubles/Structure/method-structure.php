<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Method;

trait MethodStructureDoubleTrait1
{

	public function a(string $b, string $c): void
	{
		// Noop
	}

}

final class MethodStructureDouble
{

	use MethodStructureDoubleTrait1;

}
