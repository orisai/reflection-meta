<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\ConstantsPHP82;

interface BuilderConstantPHP82DoubleInterface1
{

	public const A = 'a';

}

class BuilderConstantPHP82DoubleParent1
{

	public const B = 'b';

}

trait BuilderConstantPHP82DoubleTrait1
{

	public const C = 'c';

}

final class BuilderConstantPHP82Double extends BuilderConstantPHP82DoubleParent1 implements BuilderConstantPHP82DoubleInterface1
{

	use BuilderConstantPHP82DoubleTrait1;

	public const D1 = 'c';

	public const D2 = 'c';

}
