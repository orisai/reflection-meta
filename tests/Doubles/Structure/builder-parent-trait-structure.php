<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\ParentTrait;

trait BuilderParentTraitDoubleTrait1
{

}

class BuilderParentTraitDoubleParent1
{

	use BuilderParentTraitDoubleTrait1;

}

final class BuilderParentTraitDouble extends BuilderParentTraitDoubleParent1
{

}
