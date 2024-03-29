<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\ClassStructure;
use Orisai\ReflectionMeta\Structure\ConstantStructure;
use Orisai\ReflectionMeta\Structure\MethodStructure;
use Orisai\ReflectionMeta\Structure\PropertyStructure;
use Orisai\ReflectionMeta\Structure\StructureGroup;
use Orisai\SourceMap\ClassConstantSource;
use Orisai\SourceMap\ClassSource;
use Orisai\SourceMap\MethodSource;
use Orisai\SourceMap\PropertySource;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Classes\ClassStructureDouble1;

final class StructureGroupTest extends TestCase
{

	public function testEmpty(): void
	{
		$list = new StructureGroup([], [], [], []);
		self::assertSame([], $list->getClasses());
		self::assertSame([], $list->getGroupedConstants());
		self::assertSame([], $list->getGroupedProperties());
		self::assertSame([], $list->getGroupedMethods());
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
			'::A' => [
				new ConstantStructure(
					$reflector->getReflectionConstant('A'),
					new ClassConstantSource($reflector->getReflectionConstant('A')),
					[],
				),
			],
		];
		$properties = [
			'::b' => [
				new PropertyStructure(
					$reflector->getProperty('b'),
					new PropertySource($reflector->getProperty('b')),
					[],
				),
			],
		];
		$methods = [
			'::c' => [
				new MethodStructure(
					$reflector->getMethod('c'),
					new MethodSource($reflector->getMethod('c')),
					[],
				),
			],
		];

		$list = new StructureGroup($classes, $constants, $properties, $methods);
		self::assertSame($classes, $list->getClasses());
		self::assertSame($constants, $list->getGroupedConstants());
		self::assertSame($properties, $list->getGroupedProperties());
		self::assertSame($methods, $list->getGroupedMethods());
	}

}
