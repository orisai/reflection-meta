<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\ClassStructure;
use Orisai\ReflectionMeta\Structure\ConstantStructure;
use Orisai\ReflectionMeta\Structure\MethodStructure;
use Orisai\ReflectionMeta\Structure\PropertyStructure;
use Orisai\ReflectionMeta\Structure\StructureBuilder;
use Orisai\ReflectionMeta\Structure\StructureFlattener;
use Orisai\ReflectionMeta\Structure\StructureGrouper;
use Orisai\SourceMap\ClassConstantSource;
use Orisai\SourceMap\ClassSource;
use Orisai\SourceMap\MethodSource;
use Orisai\SourceMap\PropertySource;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionProperty;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Grouper\GrouperStructureDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Grouper\GrouperStructureInterface1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Grouper\GrouperStructureParent1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Grouper\GrouperStructureTrait1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\GrouperPHP81\GrouperStructureDoublePHP81;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\GrouperPHP81\GrouperStructureInterface1PHP81;
use const PHP_VERSION_ID;

final class StructureGrouperTest extends TestCase
{

	public function test(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/grouper-structure.php';

		$structure = StructureBuilder::build(new ReflectionClass(GrouperStructureDouble::class));
		$list = StructureFlattener::flatten($structure);
		$group = StructureGrouper::group($list);

		self::assertEquals(
			$group->getClasses(),
			[
				new ClassStructure(
					new ReflectionClass(GrouperStructureParent1::class),
					new ClassSource(new ReflectionClass(GrouperStructureParent1::class)),
				),
				new ClassStructure(
					new ReflectionClass(GrouperStructureDouble::class),
					new ClassSource(new ReflectionClass(GrouperStructureInterface1::class)),
				),
				new ClassStructure(
					new ReflectionClass(GrouperStructureDouble::class),
					new ClassSource(new ReflectionClass(GrouperStructureTrait1::class)),
				),
				new ClassStructure(
					new ReflectionClass(GrouperStructureDouble::class),
					new ClassSource(new ReflectionClass(GrouperStructureDouble::class)),
				),
			],
		);

		self::assertEquals(
			$group->getGroupedConstants(),
			[
				'::A' => [
					new ConstantStructure(
						new ReflectionClassConstant(GrouperStructureParent1::class, 'A'),
						new ClassConstantSource(new ReflectionClassConstant(GrouperStructureParent1::class, 'A')),
						[],
					),
					new ConstantStructure(
						new ReflectionClassConstant(GrouperStructureDouble::class, 'A'),
						new ClassConstantSource(new ReflectionClassConstant(GrouperStructureDouble::class, 'A')),
						[],
					),
				],
				GrouperStructureParent1::class . '::D' => [
					new ConstantStructure(
						new ReflectionClassConstant(GrouperStructureParent1::class, 'D'),
						new ClassConstantSource(new ReflectionClassConstant(GrouperStructureParent1::class, 'D')),
						[],
					),
				],
				'::B' => [
					new ConstantStructure(
						new ReflectionClassConstant(GrouperStructureDouble::class, 'B'),
						new ClassConstantSource(new ReflectionClassConstant(GrouperStructureInterface1::class, 'B')),
						[],
					),
				],
				'::C' => [
					new ConstantStructure(
						new ReflectionClassConstant(GrouperStructureDouble::class, 'C'),
						new ClassConstantSource(new ReflectionClassConstant(GrouperStructureDouble::class, 'C')),
						[],
					),
				],
				GrouperStructureDouble::class . '::D' => [
					new ConstantStructure(
						new ReflectionClassConstant(GrouperStructureDouble::class, 'D'),
						new ClassConstantSource(new ReflectionClassConstant(GrouperStructureDouble::class, 'D')),
						[],
					),
				],
			],
		);

		self::assertEquals(
			$group->getGroupedProperties(),
			[
				'::a' => [
					new PropertyStructure(
						new ReflectionProperty(GrouperStructureParent1::class, 'a'),
						new PropertySource(new ReflectionProperty(GrouperStructureParent1::class, 'a')),
						[],
					),
					new PropertyStructure(
						new ReflectionProperty(GrouperStructureDouble::class, 'a'),
						new PropertySource(new ReflectionProperty(GrouperStructureDouble::class, 'a')),
						[],
					),
				],
				GrouperStructureParent1::class . '::d' => [
					new PropertyStructure(
						new ReflectionProperty(GrouperStructureParent1::class, 'd'),
						new PropertySource(new ReflectionProperty(GrouperStructureParent1::class, 'd')),
						[],
					),
				],
				'::b' => [
					new PropertyStructure(
						new ReflectionProperty(GrouperStructureParent1::class, 'b'),
						new PropertySource(new ReflectionProperty(GrouperStructureParent1::class, 'b')),
						[],
					),
					new PropertyStructure(
						new ReflectionProperty(GrouperStructureDouble::class, 'b'),
						new PropertySource(new ReflectionProperty(GrouperStructureDouble::class, 'b')),
						[],
					),
				],
				'::c' => [
					new PropertyStructure(
						new ReflectionProperty(GrouperStructureDouble::class, 'c'),
						new PropertySource(new ReflectionProperty(GrouperStructureDouble::class, 'c')),
						[],
					),
				],
				GrouperStructureDouble::class . '::d' => [
					new PropertyStructure(
						new ReflectionProperty(GrouperStructureDouble::class, 'd'),
						new PropertySource(new ReflectionProperty(GrouperStructureDouble::class, 'd')),
						[],
					),
				],
			],
		);

		self::assertEquals(
			$group->getGroupedMethods(),
			[
				'::a' => [
					new MethodStructure(
						new ReflectionMethod(GrouperStructureParent1::class, 'a'),
						new MethodSource(new ReflectionMethod(GrouperStructureParent1::class, 'a')),
						[],
					),
					new MethodStructure(
						new ReflectionMethod(GrouperStructureDouble::class, 'a'),
						new MethodSource(new ReflectionMethod(GrouperStructureDouble::class, 'a')),
						[],
					),
				],
				GrouperStructureParent1::class . '::d' => [
					new MethodStructure(
						new ReflectionMethod(GrouperStructureParent1::class, 'd'),
						new MethodSource(new ReflectionMethod(GrouperStructureParent1::class, 'd')),
						[],
					),
				],
				'::b' => [
					new MethodStructure(
						new ReflectionMethod(GrouperStructureDouble::class, 'b'),
						new MethodSource(new ReflectionMethod(GrouperStructureInterface1::class, 'b')),
						[],
					),
					new MethodStructure(
						new ReflectionMethod(GrouperStructureDouble::class, 'b'),
						new MethodSource(new ReflectionMethod(GrouperStructureDouble::class, 'b')),
						[],
					),
				],
				'::c' => [
					new MethodStructure(
						new ReflectionMethod(GrouperStructureDouble::class, 'c'),
						new MethodSource(new ReflectionMethod(GrouperStructureDouble::class, 'c')),
						[],
					),
				],
				GrouperStructureDouble::class . '::d' => [
					new MethodStructure(
						new ReflectionMethod(GrouperStructureDouble::class, 'd'),
						new MethodSource(new ReflectionMethod(GrouperStructureDouble::class, 'd')),
						[],
					),
				],
			],
		);
	}

	public function testPHP81(): void
	{
		if (PHP_VERSION_ID < 8_01_00) {
			self::markTestSkipped('Overriding constants from interfaces is valid since PHP 8.1');
		}

		require_once __DIR__ . '/../../Doubles/Structure/grouper-structure-php8.1.php';

		$structure = StructureBuilder::build(new ReflectionClass(GrouperStructureDoublePHP81::class));
		$list = StructureFlattener::flatten($structure);
		$group = StructureGrouper::group($list);

		self::assertEquals(
			$group->getGroupedConstants(),
			[
				'::B' => [
					new ConstantStructure(
						new ReflectionClassConstant(GrouperStructureDoublePHP81::class, 'B'),
						new ClassConstantSource(
							new ReflectionClassConstant(GrouperStructureInterface1PHP81::class, 'B'),
						),
						[],
					),
					new ConstantStructure(
						new ReflectionClassConstant(GrouperStructureDoublePHP81::class, 'B'),
						new ClassConstantSource(new ReflectionClassConstant(GrouperStructureDoublePHP81::class, 'B')),
						[],
					),
				],
			],
		);
	}

}
