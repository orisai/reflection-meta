<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\PropertyWithDuplicatesStructure;
use Orisai\SourceMap\PropertySource;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Property\PropertyStructureDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Property\PropertyStructureDoubleTrait1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Property\PropertyStructureDoubleTrait2;

final class PropertyWithDuplicatesStructureTest extends TestCase
{

	public function testBase(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/property-structure.php';

		$reflector = new ReflectionProperty(
			PropertyStructureDouble::class,
			'a',
		);

		$contextClass = $reflector->getDeclaringClass();
		$duplicates = [];
		$source = new PropertySource($reflector);

		$property = new PropertyWithDuplicatesStructure($contextClass, $duplicates, $source);

		self::assertSame($contextClass, $property->getContextClass());
		self::assertSame($duplicates, $property->getDuplicateDeclarations());
		self::assertSame($source, $property->getSource());
	}

	public function testExtra(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/property-structure.php';

		$reflector = new ReflectionProperty(
			PropertyStructureDouble::class,
			'a',
		);

		$contextClass = $reflector->getDeclaringClass();
		$duplicates = [
			new ReflectionClass(PropertyStructureDoubleTrait1::class),
			new ReflectionClass(PropertyStructureDoubleTrait2::class),
		];
		$source = new PropertySource($reflector);

		$property = new PropertyWithDuplicatesStructure($contextClass, $duplicates, $source);

		self::assertSame($contextClass, $property->getContextClass());
		self::assertSame($duplicates, $property->getDuplicateDeclarations());
		self::assertSame($source, $property->getSource());
	}

}
