<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Property;

trait PropertyStructureDoubleTrait1
{

	public string $a;

}

trait PropertyStructureDoubleTrait2
{

	use PropertyStructureDoubleTrait1;

}

final class PropertyStructureDouble
{

	use PropertyStructureDoubleTrait2;

}
