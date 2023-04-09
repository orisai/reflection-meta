<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\ClassConstantStructure;
use Orisai\ReflectionMeta\Structure\HierarchyClassStructure;
use Orisai\ReflectionMeta\Structure\MethodStructure;
use Orisai\ReflectionMeta\Structure\PropertyWithDuplicatesStructure;
use Orisai\SourceMap\ClassConstantSource;
use Orisai\SourceMap\ClassSource;
use Orisai\SourceMap\MethodSource;
use Orisai\SourceMap\PropertySource;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionProperty;
use stdClass;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Classes\ClassStructureDouble1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Classes\ClassStructureDoubleInterface1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Classes\ClassStructureDoubleTrait1;

final class HierarchyClassStructureTest extends TestCase
{

	public function testBase(): void
	{
		$reflector = new ReflectionClass(stdClass::class);
		$source = new ClassSource($reflector);

		$class = new HierarchyClassStructure(
			$reflector,
			null,
			[],
			[],
			[],
			[],
			[],
			$source,
		);

		self::assertSame($reflector, $class->getContextClass());
		self::assertNull($class->getParent());
		self::assertSame([], $class->getInterfaces());
		self::assertSame([], $class->getTraits());
		self::assertSame([], $class->getConstants());
		self::assertSame([], $class->getProperties());
		self::assertSame([], $class->getMethods());
		self::assertSame($source, $class->getSource());
	}

	public function testExtra(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/hierarchy-class-structure.php';

		$reflector = new ReflectionClass(ClassStructureDouble1::class);
		$source = new ClassSource($reflector);

		$class = new HierarchyClassStructure(
			$reflector,
			$parent = new HierarchyClassStructure(
				new ReflectionClass(stdClass::class),
				null,
				[],
				[],
				[],
				[],
				[],
				new ClassSource(new ReflectionClass(stdClass::class)),
			),
			$interfaces = [
				new HierarchyClassStructure(
					$reflector,
					null,
					[],
					[],
					[],
					[],
					[],
					new ClassSource(new ReflectionClass(ClassStructureDoubleInterface1::class)),
				),
			],
			$traits = [
				new HierarchyClassStructure(
					$reflector,
					null,
					[],
					[],
					[],
					[],
					[],
					new ClassSource(new ReflectionClass(ClassStructureDoubleTrait1::class)),
				),
			],
			$constants = [
				new ClassConstantStructure(
					$reflector,
					new ClassConstantSource(
						new ReflectionClassConstant(ClassStructureDouble1::class, 'A'),
					),
					[],
				),
			],
			$properties = [
				new PropertyWithDuplicatesStructure(
					$reflector,
					new PropertySource(
						new ReflectionProperty(ClassStructureDouble1::class, 'b'),
					),
					[],
				),
			],
			$methods = [
				new MethodStructure(
					$reflector,
					[],
					new MethodSource(
						new ReflectionMethod(ClassStructureDouble1::class, 'c'),
					),
				),
			],
			$source,
		);

		self::assertSame($parent, $class->getParent());
		self::assertSame($interfaces, $class->getInterfaces());
		self::assertSame($traits, $class->getTraits());
		self::assertSame($constants, $class->getConstants());
		self::assertSame($properties, $class->getProperties());
		self::assertSame($methods, $class->getMethods());
		self::assertSame($source, $class->getSource());
	}

}
