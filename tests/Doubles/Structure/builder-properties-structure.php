<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Properties;

trait BuilderPropertyDoubleTrait1
{

	public string $a;

}

class BuilderPropertyDoubleParent1
{

	use BuilderPropertyDoubleTrait1;

	public string $b;

}

trait BuilderPropertyDoubleTrait2
{

	public string $c;

}

final class BuilderPropertyDouble extends BuilderPropertyDoubleParent1
{

	use BuilderPropertyDoubleTrait2;

	public string $d1;

	public string $d2;

}
