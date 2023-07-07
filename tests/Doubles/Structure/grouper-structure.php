<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Grouper;

class GrouperStructureParent1
{

	protected const A = 'a';

	/**
	 * @phpstan-ignore-next-line Used via reflection
	 */
	private const D = 'd';

	protected string $a;

	public string $b;

	/** @phpstan-ignore-next-line Used via reflection */
	private string $d;

	protected function a(): void
	{
		// Noop
	}

	/**
	 * @phpstan-ignore-next-line Used via reflection
	 */
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

	/**
	 * @phpstan-ignore-next-line Used via reflection
	 */
	private const D = 'd';

	protected string $a;

	public string $b;

	public string $c;

	/** @phpstan-ignore-next-line Used via reflection */
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

	/**
	 * @phpstan-ignore-next-line Used via reflection
	 */
	private function d(): void
	{
		// Noop
	}

}
