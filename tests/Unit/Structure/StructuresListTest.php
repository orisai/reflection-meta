<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\ClassConstantStructure;
use Orisai\ReflectionMeta\Structure\ClassStructure;
use Orisai\ReflectionMeta\Structure\MethodStructure;
use Orisai\ReflectionMeta\Structure\PropertyStructure;
use Orisai\ReflectionMeta\Structure\StructuresList;
use Orisai\SourceMap\ClassConstantSource;
use Orisai\SourceMap\ClassSource;
use Orisai\SourceMap\MethodSource;
use Orisai\SourceMap\PropertySource;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Classes\ClassStructureDouble1;

final class StructuresListTest extends TestCase
{

	public function testEmpty(): void
	{
		$list = new StructuresList([], [], [], []);
		self::assertSame([], $list->getClasses());
		self::assertSame([], $list->getConstants());
		self::assertSame([], $list->getProperties());
		self::assertSame([], $list->getMethods());
	}

	public function testBase(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/hierarchy-class-structure.php';

		$reflector = new ReflectionClass(ClassStructureDouble1::class);

		$classes = [
			new ClassStructure(
				new ReflectionClass(stdClass::class),
				new ClassSource(new ReflectionClass(stdClass::class)),
			),
			new ClassStructure($reflector, new ClassSource($reflector)),
		];
		$constants = [
			new ClassConstantStructure(
				$reflector,
				new ClassConstantSource($reflector->getReflectionConstant('A')),
				[],
			),
		];
		$properties = [
			new PropertyStructure(
				$reflector,
				new PropertySource($reflector->getProperty('b')),
				[],
			),
		];
		$methods = [
			new MethodStructure(
				$reflector,
				[],
				new MethodSource($reflector->getMethod('c')),
			),
		];

		$list = new StructuresList($classes, $constants, $properties, $methods);
		self::assertSame($classes, $list->getClasses());
		self::assertSame($constants, $list->getConstants());
		self::assertSame($properties, $list->getProperties());
		self::assertSame($methods, $list->getMethods());
	}

}
