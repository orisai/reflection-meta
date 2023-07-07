<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\PropertyStructure;
use Orisai\SourceMap\PropertySource;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Property\PropertyStructureDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Property\PropertyStructureDoubleTrait1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Property\PropertyStructureDoubleTrait2;

final class PropertyStructureTest extends TestCase
{

	public function testBase(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/property-structure.php';

		$reflector = new ReflectionProperty(
			PropertyStructureDouble::class,
			'a',
		);

		$duplicators = [];
		$source = new PropertySource($reflector);

		$property = new PropertyStructure($reflector, $source, $duplicators);

		self::assertSame($reflector, $property->getContextReflector());
		self::assertSame($duplicators, $property->getDuplicators());
		self::assertSame($source, $property->getSource());
	}

	public function testDuplicators(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/property-structure.php';

		$reflector = new ReflectionProperty(
			PropertyStructureDouble::class,
			'a',
		);

		$duplicators = [
			new ReflectionClass(PropertyStructureDoubleTrait1::class),
			new ReflectionClass(PropertyStructureDoubleTrait2::class),
		];
		$source = new PropertySource($reflector);

		$property = new PropertyStructure($reflector, $source, $duplicators);

		self::assertSame($duplicators, $property->getDuplicators());
	}

}
