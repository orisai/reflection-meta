<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Constants;

interface BuilderConstantDoubleInterface1
{

	public const A = 'a';

}

class BuilderConstantDoubleParent1
{

	public const B = 'b';

}

final class BuilderConstantDouble extends BuilderConstantDoubleParent1 implements BuilderConstantDoubleInterface1
{

	public const C1 = 'c';

	public const C2 = 'c';

}
