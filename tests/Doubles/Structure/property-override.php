<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\PropertyOverride;

trait OverridePropertyParentTrait
{

	/**
	 * Trait
	 */
	public string $test;

}

class OverridePropertyParent
{

	use OverridePropertyParentTrait;

	/**
	 * Parent
	 */
	public string $test;

}

class OverridePropertyChild extends OverridePropertyParent
{

}
