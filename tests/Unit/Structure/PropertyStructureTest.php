<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\PropertyStructure;
use Orisai\SourceMap\PropertySource;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Property\PropertyStructureDouble;

final class PropertyStructureTest extends TestCase
{

	public function testBase(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/property-structure.php';

		$reflector = new ReflectionProperty(
			PropertyStructureDouble::class,
			'a',
		);

		$contextClass = $reflector->getDeclaringClass();
		$source = new PropertySource($reflector);

		$property = new PropertyStructure($contextClass, $source);

		self::assertSame($contextClass, $property->getContextClass());
		self::assertSame($source, $property->getSource());
	}

}
