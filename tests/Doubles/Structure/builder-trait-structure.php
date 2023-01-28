<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Traits;

trait BuilderTraitDoubleTrait1
{

}

trait BuilderTraitDoubleTrait2
{

	use BuilderTraitDoubleTrait1;

}

trait BuilderTraitDoubleTrait3
{

	use BuilderTraitDoubleTrait1;

}

final class BuilderTraitDouble
{

	use BuilderTraitDoubleTrait2;
	use BuilderTraitDoubleTrait3;

}
