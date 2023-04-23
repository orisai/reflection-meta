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
use Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatiblePropertiesTraitsPHP81\A1 as Compat81A;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatiblePropertiesTraitsPHP81\B1 as Compat81B;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatiblePropertiesTraitsPHP81\CompatiblePropertiesTraitsPHP81Class;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraits\A1 as IncompatA1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraits\A2 as IncompatA2;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraits\B1 as IncompatB1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraits\IncompatiblePropertiesTraitsClass;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraitsPHP81\A1 as Incompat81A1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraitsPHP81\A2 as Incompat81A2;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraitsPHP81\B1 as Incompat81B1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraitsPHP81\IncompatiblePropertiesTraitsPHP81Class;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\NoTraitsClass;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\UniquePropertiesTraits\A1 as UniqueA1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\UniquePropertiesTraits\B1 as UniqueB1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\UniquePropertiesTraits\UniquePropertiesTraitsClass;
use const PHP_VERSION_ID;

final class PropertyDeclaratorFinderTest extends TestCase
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

	public function testTraitsFoundPHP81(): void
	{
		if (PHP_VERSION_ID < 8_01_00) {
			self::markTestSkipped('Nested attributes are valid since PHP 8.1');
		}

		require_once __DIR__ . '/../../Doubles/Finder/compatible-properties-traits-php8.1.php';

		$property = new ReflectionProperty(CompatiblePropertiesTraitsPHP81Class::class, 'a');
		$traits = PropertyDeclaratorFinder::getDeclaringTraits($property);

		self::assertEquals(
			[
				new ReflectionClass(Compat81A::class),
				new ReflectionClass(Compat81B::class),
			],
			$traits,
		);
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

		if ($propertyName !== 'b') {
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

	public function testIncompatiblePropertiesPHP81(): void
	{
		if (PHP_VERSION_ID < 8_01_00) {
			self::markTestSkipped('Nested attributes are valid since PHP 8.1');
		}

		require_once __DIR__ . '/../../Doubles/Finder/incompatible-properties-traits-php8.1.php';

		$property = new ReflectionProperty(IncompatiblePropertiesTraitsPHP81Class::class, 'a');

		self::assertEquals(
			[
				new ReflectionClass(Incompat81A1::class),
				new ReflectionClass(Incompat81A2::class),
				new ReflectionClass(Incompat81B1::class),
			],
			PropertyDeclaratorFinder::getDeclaringTraits($property),
		);
	}

	/**
	 * @param ReflectionClass<object> $declarator
	 *
	 * @dataProvider provideUniqueConstants
	 */
	public function testUniqueConstants(string $propertyName, ReflectionClass $declarator): void
	{
		$constant = new ReflectionProperty(UniquePropertiesTraitsClass::class, $propertyName);
		$traits = PropertyDeclaratorFinder::getDeclaringTraits($constant);

		self::assertEquals(
			[
				$declarator,
			],
			$traits,
		);
	}

	public function provideUniqueConstants(): Generator
	{
		require_once __DIR__ . '/../../Doubles/Finder/unique-properties-traits.php';

		yield ['a', new ReflectionClass(UniqueA1::class)];
		yield ['b', new ReflectionClass(UniqueB1::class)];
	}

}
