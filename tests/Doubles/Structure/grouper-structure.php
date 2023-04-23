<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Grouper;

class GrouperStructureParent1
{

	protected const A = 'a';

	private const D = 'd';

	protected string $a;

	public string $b;

	private string $d;

	protected function a(): void
	{
		// Noop
	}

	private function d(): void
	{
		// Noop
	}

}

interface GrouperStructureInterface1
{

	public const B = 'b';

	public function b(): void;

}

trait GrouperStructureTrait1
{

}

final class GrouperStructureDouble extends GrouperStructureParent1 implements GrouperStructureInterface1
{

	use GrouperStructureTrait1;

	protected const A = 'a';

	public const C = 'c';

	private const D = 'd';

	protected string $a;

	public string $b;

	public string $c;

	private string $d;

	protected function a(): void
	{
		// Noop
	}

	public function b(): void
	{
		// Noop
	}

	public function c(): void
	{
		// Noop
	}

	private function d(): void
	{
		// Noop
	}

}
