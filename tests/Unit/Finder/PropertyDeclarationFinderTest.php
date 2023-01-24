<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Finder;

use Generator;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\ReflectionMeta\Finder\PropertyDeclaratorFinder;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatiblePropertiesTraits\A1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatiblePropertiesTraits\B1;
use Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatiblePropertiesTraits\CompatiblePropertiesTraitsClass;
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
				new ReflectionClass(A1::class),
				new ReflectionClass(B1::class),
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

		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage(
			'Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraits\A1'
			. ' and Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraits\B1'
			. " define the same property (\$$propertyName) in the composition of"
			. ' Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraits\IncompatiblePropertiesTraitsClass.'
			. ' However, the definition differs and is considered incompatible.',
		);

		PropertyDeclaratorFinder::getDeclaringTraits($property);
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
