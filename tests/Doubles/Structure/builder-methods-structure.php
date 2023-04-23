<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Methods;

class BuilderMethodDoubleParent2
{

	public function e(): void
	{
		// Noop
	}

}

trait BuilderMethodDoubleTrait1
{

	public function a(): void
	{
		// Noop
	}

}

class BuilderMethodDoubleParent1 extends BuilderMethodDoubleParent2
{

	use BuilderMethodDoubleTrait1;

	public function b(): void
	{
		// Noop
	}

}

interface BuilderMethodDoubleInterface2
{

	public function c(): void;

}

trait BuilderMethodDoubleTrait2
{

	public function c(): void
	{
		// Noop
	}

}

final class BuilderMethodDouble extends BuilderMethodDoubleParent1 implements BuilderMethodDoubleInterface2
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
