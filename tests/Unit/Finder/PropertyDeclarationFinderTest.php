<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Finder;

use Generator;
use Orisai\ReflectionMeta\Finder\PropertyDeclaratorFinder;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatiblePropertiesTraits\A1 as CompatA1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatiblePropertiesTraits\B1 as CompatB1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatiblePropertiesTraits\CompatiblePropertiesTraitsClass;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraits\A1 as IncompatA1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraits\A2 as IncompatA2;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraits\B1 as IncompatB1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraits\IncompatiblePropertiesTraitsClass;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\NoTraitsClass;
use const PHP_VERSION_ID;

final class PropertyDeclarationFinderTest extends TestCase
{

	public function testNoTraits(): void
	{
		$property = new ReflectionProperty(NoTraitsClass::class, 'a');

		self::assertSame(
			[],
			PropertyDeclaratorFinder::getDeclaringTraits($property),
		);
	}

	/**
	 * @dataProvider provideTraitsFound
	 */
	public function testTraitsFound(string $propertyName): void
	{
		require_once __DIR__ . '/../../Doubles/Finder/compatible-properties-traits.php';

		$property = new ReflectionProperty(CompatiblePropertiesTraitsClass::class, $propertyName);
		$traits = PropertyDeclaratorFinder::getDeclaringTraits($property);

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

		if (PHP_VERSION_ID >= 8_00_00) {
			yield ['c'];
		}
	}

	public function testNoTraitsFound(): void
	{
		require_once __DIR__ . '/../../Doubles/Finder/compatible-properties-traits.php';

		$property = new ReflectionProperty(CompatiblePropertiesTraitsClass::class, 'd');
		$traits = PropertyDeclaratorFinder::getDeclaringTraits($property);

		self::assertSame(
			[],
			$traits,
		);
	}

	/**
	 * @dataProvider provideIncompatibleProperties
	 */
	public function testIncompatibleProperties(string $propertyName): void
	{
		require_once __DIR__ . '/../../Doubles/Finder/incompatible-properties-traits.php';

		$property = new ReflectionProperty(IncompatiblePropertiesTraitsClass::class, $propertyName);

		if ($propertyName === 'a') {
			self::assertEquals(
				[
					new ReflectionClass(IncompatA1::class),
					new ReflectionClass(IncompatA2::class),
					new ReflectionClass(IncompatB1::class),
				],
				PropertyDeclaratorFinder::getDeclaringTraits($property),
			);

		} else {
			self::assertEquals(
				[
					new ReflectionClass(IncompatA1::class),
					new ReflectionClass(IncompatB1::class),
				],
				PropertyDeclaratorFinder::getDeclaringTraits($property),
			);

		}
	}

	public function provideIncompatibleProperties(): Generator
	{
		yield ['a'];
		yield ['b'];

		if (PHP_VERSION_ID >= 8_00_00) {
			yield ['c'];
			yield ['d'];
			yield ['e'];
			yield ['f'];
		}
	}

}
