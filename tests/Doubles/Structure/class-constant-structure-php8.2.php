<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\ClassConstantPHP82;

trait PropertyStructurePHP82DoubleTrait1
{

	public const A = 'a';

}

trait PropertyStructurePHP82DoubleTrait2
{

	use PropertyStructurePHP82DoubleTrait1;

}

final class ClassConstantStructurePHP82Double
{

	use PropertyStructurePHP82DoubleTrait2;

	public const A = 'a';

}
