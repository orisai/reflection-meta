<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Flattener;

interface FlattenerStructureParentInterface1
{

	public const ParentInterface1 = 'a';

	public const ParentInterface2 = 'a';

	public function parentInterface1(): void;

}

interface FlattenerStructureParentInterface2
{

}

trait FlattenerStructureParentTrait1
{

	public string $parentTrait1;

	public string $parentTrait2;

	public function parentTrait1(): void
	{
		// Noop
	}

	public function parentTrait2(): void
	{
		// Noop
	}

}

trait FlattenerStructureParentTrait2
{

}

class FlattenerStructureParent1 implements FlattenerStructureParentInterface1, FlattenerStructureParentInterface2
{

	use FlattenerStructureParentTrait1;
	use FlattenerStructureParentTrait2;

	public function parentInterface1(): void
	{
		// Noop
	}

}

interface FlattenerStructureInterface1
{

	public function childInterface1(): void;

}

interface FlattenerStructureInterface2
{

}

trait FlattenerStructureTrait1
{

	public function childTrait1(): void
	{
		// Noop
	}

	public function childTrait2(): void
	{
		// Noop
	}

}

trait FlattenerStructureTrait2
{

}

final class FlattenerStructureDouble
	extends FlattenerStructureParent1
	implements FlattenerStructureInterface1, FlattenerStructureInterface2
{

	use FlattenerStructureTrait1;
	use FlattenerStructureTrait2;

	public function childInterface1(): void
	{
		// Noop
	}

}
