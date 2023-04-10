<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Finder;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\ReflectionMeta\Finder\MethodDeclaratorFinder;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\NoTraitsClass;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\SameLineTraits\SameLineTraitsClass;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\SameLineTraitsStatic\SameLineTraitsStaticClass;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\Traits\A1 as TraitsA1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\Traits\TraitsClass;

final class MethodDeclaratorFinderTest extends TestCase
{

	public function testNoTraits(): void
	{
		$method = new ReflectionMethod(NoTraitsClass::class, 'a');

		self::assertNull(MethodDeclaratorFinder::getDeclaringTrait($method));
	}

	public function testTraitsFound(): void
	{
		require_once __DIR__ . '/../../Doubles/Finder/traits.php';

		$method = new ReflectionMethod(TraitsClass::class, 'a');
		$trait = MethodDeclaratorFinder::getDeclaringTrait($method);

		self::assertNotNull($trait);
		self::assertSame(TraitsA1::class, $trait->getName());
	}

	public function testTraitsNotFound(): void
	{
		require_once __DIR__ . '/../../Doubles/Finder/traits.php';

		$method = new ReflectionMethod(TraitsClass::class, 'b');
		$trait = MethodDeclaratorFinder::getDeclaringTrait($method);

		self::assertNull($trait);
	}

	public function testSameLineDeclarations(): void
	{
		require_once __DIR__ . '/../../Doubles/Finder/same-line-traits.php';

		$method = new ReflectionMethod(SameLineTraitsClass::class, 'a');

		$this->expectException(InvalidState::class);
		/**
		 * B1 is also reported even though 'insteadof' is used because we don't have any way to detect 'insteadof'
		 * without using PHP parser, but it doesn't matter because reflected class is returned properly
		 *
		 * @see self::testTraitsFound()
		 */
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Checking which trait declared method
         'Tests\Orisai\ReflectionMeta\Doubles\Finder\SameLineTraits\SameLineTraitsClass->a()'
Problem: These traits are on the same line:
         'Tests\Orisai\ReflectionMeta\Doubles\Finder\SameLineTraits\A1',
         'Tests\Orisai\ReflectionMeta\Doubles\Finder\SameLineTraits\A2',
         'Tests\Orisai\ReflectionMeta\Doubles\Finder\SameLineTraits\A3',
         'Tests\Orisai\ReflectionMeta\Doubles\Finder\SameLineTraits\B1'
Solution: Don't place them on the same line.
MSG,
		);

		MethodDeclaratorFinder::getDeclaringTrait($method);
	}

	public function testSameLineStaticDeclarations(): void
	{
		require_once __DIR__ . '/../../Doubles/Finder/same-line-traits-static.php';

		$method = new ReflectionMethod(SameLineTraitsStaticClass::class, 'a');

		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Checking which trait declared method
         'Tests\Orisai\ReflectionMeta\Doubles\Finder\SameLineTraitsStatic\SameLineTraitsStaticClass::a()'
Problem: These traits are on the same line:
         'Tests\Orisai\ReflectionMeta\Doubles\Finder\SameLineTraitsStatic\A1',
         'Tests\Orisai\ReflectionMeta\Doubles\Finder\SameLineTraitsStatic\A2'
Solution: Don't place them on the same line.
MSG,
		);

		MethodDeclaratorFinder::getDeclaringTrait($method);
	}

}
