<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder\Traits;

trait A1
{

	public function a(): void
	{
	}

}

trait A2
{

	use A1;

}

trait A3
{

	use A2;

}

trait B1
{

	public function a(): void
	{
	}

}

class TraitsClass
{

	use A3, B1 {
		A3::a insteadof B1;
	}

	public function b(): void
	{
		// Noop
	}

}
