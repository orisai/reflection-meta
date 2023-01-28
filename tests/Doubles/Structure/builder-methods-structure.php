<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Methods;

trait BuilderMethodDoubleTrait1
{

	public function a(): void
	{
		// Noop
	}

}

class BuilderMethodDoubleParent1
{

	use BuilderMethodDoubleTrait1;

	public function b(): void
	{
		// Noop
	}

}

trait BuilderMethodDoubleTrait2
{

	public function c(): void
	{
		// Noop
	}

}

final class BuilderMethodDouble extends BuilderMethodDoubleParent1
{

	use BuilderMethodDoubleTrait2;

	public function d1(): void
	{
		// Noop
	}

	public function d2(string $a, string $b): void
	{
		// Noop
	}

}
