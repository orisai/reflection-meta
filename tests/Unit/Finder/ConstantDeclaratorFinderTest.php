<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Finder;

use Generator;
use Orisai\ReflectionMeta\Finder\ConstantDeclaratorFinder;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionClassConstant;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatibleConstantsTraits\A1 as CompatA1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatibleConstantsTraits\B1 as CompatB1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatibleConstantsTraits\CompatibleConstantsTraitsClass;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatibleConstantsTraits\A1 as IncompatA1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatibleConstantsTraits\A2 as IncompatA2;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatibleConstantsTraits\B1 as IncompatB1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatibleConstantsTraits\IncompatibleConstantsTraitsClass;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\NoTraitsClass;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\UniqueConstantsTraits\A1 as UniqueA1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\UniqueConstantsTraits\B1 as UniqueB1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\UniqueConstantsTraits\UniqueConstantsTraitsClass;
use const PHP_VERSION_ID;

final class ConstantDeclaratorFinderTest extends TestCase
{

	protected function setUp(): void
	{
		if (PHP_VERSION_ID < 8_02_00) {
			self::markTestSkipped('Constants on traits are valid since PHP 8.2');
		}
	}

	public function testNoTraits(): void
	{
		$constant = new ReflectionClassConstant(NoTraitsClass::class, 'a');

		self::assertSame(
			[],
			ConstantDeclaratorFinder::getDeclaringTraits($constant),
		);
	}

	/**
	 * @dataProvider provideTraitsFound
	 */
	public function testTraitsFound(string $constantName): void
	{
		require_once __DIR__ . '/../../Doubles/Finder/compatible-constants-traits.php';

		$constant = new ReflectionClassConstant(CompatibleConstantsTraitsClass::class, $constantName);
		$traits = ConstantDeclaratorFinder::getDeclaringTraits($constant);

		self::assertEquals(
			[
				new ReflectionClass(CompatA1::class),
				new ReflectionClass(CompatB1::class),
			],
			$traits,
		);
	}

	public function provideTraitsFound(): Generator
	{
		yield ['a'];
		yield ['b'];
		yield ['c'];
	}

	public function testNoTraitsFound(): void
	{
		require_once __DIR__ . '/../../Doubles/Finder/compatible-constants-traits.php';

		$constant = new ReflectionClassConstant(CompatibleConstantsTraitsClass::class, 'd');
		$traits = ConstantDeclaratorFinder::getDeclaringTraits($constant);

		self::assertSame(
			[],
			$traits,
		);
	}

	/**
	 * @dataProvider provideIncompatibleConstants
	 */
	public function testIncompatibleConstants(string $constantName): void
	{
		$constant = new ReflectionClassConstant(IncompatibleConstantsTraitsClass::class, $constantName);

		if ($constantName !== 'b') {
			self::assertEquals(
				[
					new ReflectionClass(IncompatA1::class),
					new ReflectionClass(IncompatA2::class),
					new ReflectionClass(IncompatB1::class),
				],
				ConstantDeclaratorFinder::getDeclaringTraits($constant),
			);

		} else {
			self::assertEquals(
				[
					new ReflectionClass(IncompatA1::class),
					new ReflectionClass(IncompatB1::class),
				],
				ConstantDeclaratorFinder::getDeclaringTraits($constant),
			);

		}
	}

	public function provideIncompatibleConstants(): Generator
	{
		// Must be here, otherwise provider is still executed, even with skip in setup
		if (PHP_VERSION_ID < 8_02_00) {
			self::markTestSkipped('Constants on traits are valid since PHP 8.2');
		}

		require_once __DIR__ . '/../../Doubles/Finder/incompatible-constants-traits.php';

		$reflector = new ReflectionClass(IncompatibleConstantsTraitsClass::class);

		foreach ($reflector->getConstants() as $constant) {
			yield [$constant];
		}
	}

	/**
	 * @param ReflectionClass<object> $declarator
	 *
	 * @dataProvider provideUniqueConstants
	 */
	public function testUniqueConstants(string $constantName, ReflectionClass $declarator): void
	{
		$constant = new ReflectionClassConstant(UniqueConstantsTraitsClass::class, $constantName);
		$traits = ConstantDeclaratorFinder::getDeclaringTraits($constant);

		self::assertEquals(
			[
				$declarator,
			],
			$traits,
		);
	}

	public function provideUniqueConstants(): Generator
	{
		// Must be here, otherwise provider is still executed, even with skip in setup
		if (PHP_VERSION_ID < 8_02_00) {
			self::markTestSkipped('Constants on traits are valid since PHP 8.2');
		}

		require_once __DIR__ . '/../../Doubles/Finder/unique-constants-traits.php';

		yield ['a', new ReflectionClass(UniqueA1::class)];
		yield ['b', new ReflectionClass(UniqueB1::class)];
	}

}
