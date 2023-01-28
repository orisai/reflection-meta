<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Classes;

abstract class BuilderParentDoubleParent1
{

}

abstract class BuilderParentDoubleParent2 extends BuilderParentDoubleParent1
{

}

final class BuilderParentDouble extends BuilderParentDoubleParent2
{

}
