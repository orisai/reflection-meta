<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\ClassConstantStructure;
use Orisai\ReflectionMeta\Structure\ClassStructure;
use Orisai\ReflectionMeta\Structure\MethodStructure;
use Orisai\ReflectionMeta\Structure\PropertyStructure;
use Orisai\ReflectionMeta\Structure\StructureBuilder;
use Orisai\ReflectionMeta\Structure\StructureFlattener;
use Orisai\SourceMap\ClassConstantSource;
use Orisai\SourceMap\ClassSource;
use Orisai\SourceMap\MethodSource;
use Orisai\SourceMap\PropertySource;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionProperty;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Flattener\FlattenerStructureDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Flattener\FlattenerStructureInterface1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Flattener\FlattenerStructureInterface2;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Flattener\FlattenerStructureParent1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Flattener\FlattenerStructureParentInterface1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Flattener\FlattenerStructureParentInterface2;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Flattener\FlattenerStructureParentTrait1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Flattener\FlattenerStructureParentTrait2;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Flattener\FlattenerStructureTrait1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Flattener\FlattenerStructureTrait2;

final class StructureFlattenerTest extends TestCase
{

	public function test(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/flattener-structure.php';

		$builder = new StructureBuilder();
		$flattener = new StructureFlattener();

		$structure = $builder->build(new ReflectionClass(FlattenerStructureDouble::class));
		$list = $flattener->flatten($structure);

		self::assertEquals(
			[
				new ClassStructure(
					new ReflectionClass(FlattenerStructureParent1::class),
					new ClassSource(
						new ReflectionClass(FlattenerStructureParentInterface1::class),
					),
				),
				new ClassStructure(
					new ReflectionClass(FlattenerStructureParent1::class),
					new ClassSource(
						new ReflectionClass(FlattenerStructureParentInterface2::class),
					),
				),
				new ClassStructure(
					new ReflectionClass(FlattenerStructureParent1::class),
					new ClassSource(
						new ReflectionClass(FlattenerStructureParentTrait1::class),
					),
				),
				new ClassStructure(
					new ReflectionClass(FlattenerStructureParent1::class),
					new ClassSource(
						new ReflectionClass(FlattenerStructureParentTrait2::class),
					),
				),
				new ClassStructure(
					new ReflectionClass(FlattenerStructureParent1::class),
					new ClassSource(
						new ReflectionClass(FlattenerStructureParent1::class),
					),
				),
				new ClassStructure(
					new ReflectionClass(FlattenerStructureDouble::class),
					new ClassSource(
						new ReflectionClass(FlattenerStructureInterface1::class),
					),
				),
				new ClassStructure(
					new ReflectionClass(FlattenerStructureDouble::class),
					new ClassSource(
						new ReflectionClass(FlattenerStructureInterface2::class),
					),
				),
				new ClassStructure(
					new ReflectionClass(FlattenerStructureDouble::class),
					new ClassSource(
						new ReflectionClass(FlattenerStructureTrait1::class),
					),
				),
				new ClassStructure(
					new ReflectionClass(FlattenerStructureDouble::class),
					new ClassSource(
						new ReflectionClass(FlattenerStructureTrait2::class),
					),
				),
				new ClassStructure(
					new ReflectionClass(FlattenerStructureDouble::class),
					new ClassSource(
						new ReflectionClass(FlattenerStructureDouble::class),
					),
				),
			],
			$list->getClasses(),
		);

		self::assertEquals(
			[
				new ClassConstantStructure(new ClassConstantSource(
					new ReflectionClassConstant(FlattenerStructureParentInterface1::class, 'ParentInterface1'),
				)),
				new ClassConstantStructure(new ClassConstantSource(
					new ReflectionClassConstant(FlattenerStructureParentInterface1::class, 'ParentInterface2'),
				)),
			],
			$list->getConstants(),
		);

		self::assertEquals(
			[
				new PropertyStructure(
					new ReflectionClass(FlattenerStructureParent1::class),
					new PropertySource(
						new ReflectionProperty(FlattenerStructureParentTrait1::class, 'parentTrait1'),
					),
				),
				new PropertyStructure(
					new ReflectionClass(FlattenerStructureParent1::class),
					new PropertySource(
						new ReflectionProperty(FlattenerStructureParentTrait1::class, 'parentTrait2'),
					),
				),
			],
			$list->getProperties(),
		);

		self::assertEquals(
			[
				new MethodStructure(
					new ReflectionClass(FlattenerStructureParent1::class),
					[],
					new MethodSource(
						new ReflectionMethod(FlattenerStructureParentInterface1::class, 'parentInterface1'),
					),
				),
				new MethodStructure(
					new ReflectionClass(FlattenerStructureParent1::class),
					[],
					new MethodSource(
						new ReflectionMethod(FlattenerStructureParentTrait1::class, 'parentTrait1'),
					),
				),
				new MethodStructure(
					new ReflectionClass(FlattenerStructureParent1::class),
					[],
					new MethodSource(
						new ReflectionMethod(FlattenerStructureParentTrait1::class, 'parentTrait2'),
					),
				),
				// Override of interface in class
				new MethodStructure(
					new ReflectionClass(FlattenerStructureParent1::class),
					[],
					new MethodSource(
						new ReflectionMethod(FlattenerStructureParent1::class, 'parentInterface1'),
					),
				),
				new MethodStructure(
					new ReflectionClass(FlattenerStructureDouble::class),
					[],
					new MethodSource(
						new ReflectionMethod(FlattenerStructureInterface1::class, 'childInterface1'),
					),
				),
				new MethodStructure(
					new ReflectionClass(FlattenerStructureDouble::class),
					[],
					new MethodSource(
						new ReflectionMethod(FlattenerStructureTrait1::class, 'childTrait1'),
					),
				),
				new MethodStructure(
					new ReflectionClass(FlattenerStructureDouble::class),
					[],
					new MethodSource(
						new ReflectionMethod(FlattenerStructureTrait1::class, 'childTrait2'),
					),
				),
				// Override of interface in class
				new MethodStructure(
					new ReflectionClass(FlattenerStructureDouble::class),
					[],
					new MethodSource(
						new ReflectionMethod(FlattenerStructureDouble::class, 'childInterface1'),
					),
				),
			],
			$list->getMethods(),
		);
	}

}
