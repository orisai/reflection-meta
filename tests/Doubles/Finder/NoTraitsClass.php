<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder;

final class NoTraitsClass
{

	public const a = 'a';

	public string $a;

	public function a(): void
	{
		// Noop
	}

}
