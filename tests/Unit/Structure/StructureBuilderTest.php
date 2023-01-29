<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\ClassConstantStructure;
use Orisai\ReflectionMeta\Structure\HierarchyClassStructure;
use Orisai\ReflectionMeta\Structure\MethodStructure;
use Orisai\ReflectionMeta\Structure\ParameterStructure;
use Orisai\ReflectionMeta\Structure\PropertyWithDuplicatesStructure;
use Orisai\ReflectionMeta\Structure\StructureBuilder;
use Orisai\SourceMap\ClassConstantSource;
use Orisai\SourceMap\ClassSource;
use Orisai\SourceMap\MethodSource;
use Orisai\SourceMap\ParameterSource;
use Orisai\SourceMap\PropertySource;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use stdClass;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Classes\BuilderParentDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Classes\BuilderParentDoubleParent1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Classes\BuilderParentDoubleParent2;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Constants\BuilderConstantDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Constants\BuilderConstantDoubleInterface1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Constants\BuilderConstantDoubleParent1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Interfaces\BuilderInterfaceDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Interfaces\BuilderInterfaceDoubleInterface1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Interfaces\BuilderInterfaceDoubleInterface2;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Interfaces\BuilderInterfaceDoubleInterface3;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Methods\BuilderMethodDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Methods\BuilderMethodDoubleParent1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Methods\BuilderMethodDoubleTrait1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Methods\BuilderMethodDoubleTrait2;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ParentInterface\BuilderParentInterfaceDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ParentInterface\BuilderParentInterfaceDoubleInterface1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ParentInterface\BuilderParentInterfaceDoubleParent1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ParentTrait\BuilderParentTraitDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ParentTrait\BuilderParentTraitDoubleParent1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ParentTrait\BuilderParentTraitDoubleTrait1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Properties\BuilderPropertyDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Properties\BuilderPropertyDoubleParent1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Properties\BuilderPropertyDoubleTrait1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Properties\BuilderPropertyDoubleTrait2;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Traits\BuilderTraitDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Traits\BuilderTraitDoubleTrait1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Traits\BuilderTraitDoubleTrait2;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Traits\BuilderTraitDoubleTrait3;

final class StructureBuilderTest extends TestCase
{

	public function testBase(): void
	{
		$builder = new StructureBuilder();
		$class = new ReflectionClass(stdClass::class);
		$structure = $builder->build($class);

		self::assertNull($structure->getParent());
		self::assertSame([], $structure->getInterfaces());
		self::assertSame([], $structure->getTraits());
		self::assertSame([], $structure->getConstants());
		self::assertSame([], $structure->getProperties());
		self::assertSame([], $structure->getMethods());
		self::assertEquals(
			new ClassSource(new ReflectionClass(stdClass::class)),
			$structure->getSource(),
		);
	}

	public function testParents(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/builder-parent-structure.php';

		$builder = new StructureBuilder();
		$class = new ReflectionClass(BuilderParentDouble::class);
		$structure = $builder->build($class);

		$parent1 = new HierarchyClassStructure(
			null,
			[],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderParentDoubleParent1::class)),
		);
		self::assertEquals(
			$parent1,
			$builder->build(new ReflectionClass(BuilderParentDoubleParent1::class)),
		);

		$parent2 = new HierarchyClassStructure(
			$parent1,
			[],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderParentDoubleParent2::class)),
		);
		self::assertEquals(
			$parent2,
			$builder->build(new ReflectionClass(BuilderParentDoubleParent2::class)),
		);

		self::assertEquals(
			new HierarchyClassStructure(
				$parent2,
				[],
				[],
				[],
				[],
				[],
				new ClassSource($class),
			),
			$structure,
		);
	}

	public function testInterfaces(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/builder-interface-structure.php';

		$builder = new StructureBuilder();
		$class = new ReflectionClass(BuilderInterfaceDouble::class);
		$structure = $builder->build($class);

		$i1 = new HierarchyClassStructure(
			null,
			[],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderInterfaceDoubleInterface1::class)),
		);
		self::assertEquals(
			$i1,
			$builder->build(new ReflectionClass(BuilderInterfaceDoubleInterface1::class)),
		);

		$i2 = new HierarchyClassStructure(
			null,
			[
				$i1,
			],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderInterfaceDoubleInterface2::class)),
		);
		self::assertEquals(
			$i2,
			$builder->build(new ReflectionClass(BuilderInterfaceDoubleInterface2::class)),
		);

		$i3 = new HierarchyClassStructure(
			null,
			[
				$i1,
			],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderInterfaceDoubleInterface3::class)),
		);
		self::assertEquals(
			$i3,
			$builder->build(new ReflectionClass(BuilderInterfaceDoubleInterface3::class)),
		);

		self::assertEquals(
			new HierarchyClassStructure(
				null,
				[
					$i2,
					$i3,
					// Known duplicate
					// - all interfaces are accessible in class, even if used indirectly (extended by another interface)
					// - traits don't do this
					$i1,
				],
				[],
				[],
				[],
				[],
				new ClassSource($class),
			),
			$structure,
		);
	}

	public function testTraits(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/builder-trait-structure.php';

		$builder = new StructureBuilder();
		$class = new ReflectionClass(BuilderTraitDouble::class);
		$structure = $builder->build($class);

		$t1 = new HierarchyClassStructure(
			null,
			[],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderTraitDoubleTrait1::class)),
		);
		self::assertEquals(
			$t1,
			$builder->build(new ReflectionClass(BuilderTraitDoubleTrait1::class)),
		);

		$t2 = new HierarchyClassStructure(
			null,
			[],
			[
				$t1,
			],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderTraitDoubleTrait2::class)),
		);
		self::assertEquals(
			$t2,
			$builder->build(new ReflectionClass(BuilderTraitDoubleTrait2::class)),
		);

		$t3 = new HierarchyClassStructure(
			null,
			[],
			[
				$t1,
			],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderTraitDoubleTrait3::class)),
		);
		self::assertEquals(
			$t3,
			$builder->build(new ReflectionClass(BuilderTraitDoubleTrait3::class)),
		);

		self::assertEquals(
			new HierarchyClassStructure(
				null,
				[],
				[
					$t2,
					$t3,
				],
				[],
				[],
				[],
				new ClassSource($class),
			),
			$structure,
		);
	}

	public function testParentTraits(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/builder-parent-trait-structure.php';

		$builder = new StructureBuilder();
		$class = new ReflectionClass(BuilderParentTraitDouble::class);
		$structure = $builder->build($class);

		$trait1 = new HierarchyClassStructure(
			null,
			[],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderParentTraitDoubleTrait1::class)),
		);
		self::assertEquals(
			$trait1,
			$builder->build(new ReflectionClass(BuilderParentTraitDoubleTrait1::class)),
		);

		$parent1 = new HierarchyClassStructure(
			null,
			[],
			[
				$trait1,
			],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderParentTraitDoubleParent1::class)),
		);
		self::assertEquals(
			$parent1,
			$builder->build(new ReflectionClass(BuilderParentTraitDoubleParent1::class)),
		);

		self::assertEquals(
			new HierarchyClassStructure(
				$parent1,
				[],
				[],
				[],
				[],
				[],
				new ClassSource($class),
			),
			$structure,
		);
	}

	public function testParentInterfaces(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/builder-parent-interface-structure.php';

		$builder = new StructureBuilder();
		$class = new ReflectionClass(BuilderParentInterfaceDouble::class);
		$structure = $builder->build($class);

		$interface1 = new HierarchyClassStructure(
			null,
			[],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderParentInterfaceDoubleInterface1::class)),
		);
		self::assertEquals(
			$interface1,
			$builder->build(new ReflectionClass(BuilderParentInterfaceDoubleInterface1::class)),
		);

		$parent1 = new HierarchyClassStructure(
			null,
			[
				$interface1,
			],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderParentInterfaceDoubleParent1::class)),
		);
		self::assertEquals(
			$parent1,
			$builder->build(new ReflectionClass(BuilderParentInterfaceDoubleParent1::class)),
		);

		self::assertEquals(
			new HierarchyClassStructure(
				$parent1,
				[
					$interface1,
				],
				[],
				[],
				[],
				[],
				new ClassSource($class),
			),
			$structure,
		);
	}

	public function testConstants(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/builder-constant-structure.php';

		$builder = new StructureBuilder();
		$class = new ReflectionClass(BuilderConstantDouble::class);
		$structure = $builder->build($class);

		$interface1 = new HierarchyClassStructure(
			null,
			[],
			[],
			[
				new ClassConstantStructure(new ClassConstantSource(
					new ReflectionClassConstant(BuilderConstantDoubleInterface1::class, 'A'),
				)),
			],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderConstantDoubleInterface1::class)),
		);
		self::assertEquals(
			$interface1,
			$builder->build(new ReflectionClass(BuilderConstantDoubleInterface1::class)),
		);

		$parent1 = new HierarchyClassStructure(
			null,
			[],
			[],
			[
				new ClassConstantStructure(new ClassConstantSource(
					new ReflectionClassConstant(BuilderConstantDoubleParent1::class, 'B'),
				)),
			],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderConstantDoubleParent1::class)),
		);
		self::assertEquals(
			$parent1,
			$builder->build(new ReflectionClass(BuilderConstantDoubleParent1::class)),
		);

		self::assertEquals(
			new HierarchyClassStructure(
				$parent1,
				[
					$interface1,
				],
				[],
				[
					new ClassConstantStructure(new ClassConstantSource(
						new ReflectionClassConstant(BuilderConstantDouble::class, 'C1'),
					)),
					new ClassConstantStructure(new ClassConstantSource(
						new ReflectionClassConstant(BuilderConstantDouble::class, 'C2'),
					)),
				],
				[],
				[],
				new ClassSource($class),
			),
			$structure,
		);
	}

	public function testProperties(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/builder-properties-structure.php';

		$builder = new StructureBuilder();
		$class = new ReflectionClass(BuilderPropertyDouble::class);
		$structure = $builder->build($class);

		$trait1 = new HierarchyClassStructure(
			null,
			[],
			[],
			[],
			[
				new PropertyWithDuplicatesStructure(
					new ReflectionClass(BuilderPropertyDoubleTrait1::class),
					[],
					new PropertySource(
						new ReflectionProperty(BuilderPropertyDoubleTrait1::class, 'a'),
					),
				),
			],
			[],
			new ClassSource(new ReflectionClass(BuilderPropertyDoubleTrait1::class)),
		);
		$trait1InContextOfClass = new HierarchyClassStructure(
			null,
			[],
			[],
			[],
			[
				new PropertyWithDuplicatesStructure(
					new ReflectionClass(BuilderPropertyDoubleParent1::class),
					[],
					new PropertySource(
						new ReflectionProperty(BuilderPropertyDoubleTrait1::class, 'a'),
					),
				),
			],
			[],
			new ClassSource(new ReflectionClass(BuilderPropertyDoubleTrait1::class)),
		);
		self::assertEquals(
			$trait1,
			$builder->build(new ReflectionClass(BuilderPropertyDoubleTrait1::class)),
		);
		self::assertNotEquals($trait1InContextOfClass, $trait1);

		$parent1 = new HierarchyClassStructure(
			null,
			[],
			[
				$trait1InContextOfClass,
			],
			[],
			[
				new PropertyWithDuplicatesStructure(
					new ReflectionClass(BuilderPropertyDoubleParent1::class),
					[],
					new PropertySource(
						new ReflectionProperty(BuilderPropertyDoubleParent1::class, 'b'),
					),
				),
				new PropertyWithDuplicatesStructure(
					new ReflectionClass(BuilderPropertyDoubleParent1::class),
					[
						new ReflectionClass(BuilderPropertyDoubleTrait1::class),
					],
					new PropertySource(
						new ReflectionProperty(BuilderPropertyDoubleParent1::class, 'a'),
					),
				),
			],
			[],
			new ClassSource(new ReflectionClass(BuilderPropertyDoubleParent1::class)),
		);
		self::assertEquals(
			$parent1,
			$builder->build(new ReflectionClass(BuilderPropertyDoubleParent1::class)),
		);

		$trait2 = new HierarchyClassStructure(
			null,
			[],
			[],
			[],
			[
				new PropertyWithDuplicatesStructure(
					new ReflectionClass(BuilderPropertyDoubleTrait2::class),
					[],
					new PropertySource(
						new ReflectionProperty(BuilderPropertyDoubleTrait2::class, 'c'),
					),
				),
			],
			[],
			new ClassSource(new ReflectionClass(BuilderPropertyDoubleTrait2::class)),
		);
		$trait2InContextOfClass = new HierarchyClassStructure(
			null,
			[],
			[],
			[],
			[
				new PropertyWithDuplicatesStructure(
					$class,
					[],
					new PropertySource(
						new ReflectionProperty(BuilderPropertyDoubleTrait2::class, 'c'),
					),
				),
			],
			[],
			new ClassSource(new ReflectionClass(BuilderPropertyDoubleTrait2::class)),
		);
		self::assertEquals(
			$trait2,
			$builder->build(new ReflectionClass(BuilderPropertyDoubleTrait2::class)),
		);
		self::assertNotEquals($trait2InContextOfClass, $trait2);

		self::assertEquals(
			new HierarchyClassStructure(
				$parent1,
				[],
				[
					$trait2InContextOfClass,
				],
				[],
				[
					new PropertyWithDuplicatesStructure(
						$class,
						[],
						new PropertySource(
							new ReflectionProperty(BuilderPropertyDouble::class, 'd1'),
						),
					),
					new PropertyWithDuplicatesStructure(
						$class,
						[],
						new PropertySource(
							new ReflectionProperty(BuilderPropertyDouble::class, 'd2'),
						),
					),
					new PropertyWithDuplicatesStructure(
						$class,
						[
							new ReflectionClass(BuilderPropertyDoubleTrait2::class),
						],
						new PropertySource(
							new ReflectionProperty(BuilderPropertyDouble::class, 'c'),
						),
					),
				],
				[],
				new ClassSource($class),
			),
			$structure,
		);
	}

	public function testMethods(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/builder-methods-structure.php';

		$builder = new StructureBuilder();
		$class = new ReflectionClass(BuilderMethodDouble::class);
		$structure = $builder->build($class);

		$trait1 = new HierarchyClassStructure(
			null,
			[],
			[],
			[],
			[],
			[
				new MethodStructure(
					new ReflectionClass(BuilderMethodDoubleTrait1::class),
					[],
					new MethodSource(
						new ReflectionMethod(BuilderMethodDoubleTrait1::class, 'a'),
					),
				),
			],
			new ClassSource(new ReflectionClass(BuilderMethodDoubleTrait1::class)),
		);
		$trait1InContextOfClass = new HierarchyClassStructure(
			null,
			[],
			[],
			[],
			[],
			[
				new MethodStructure(
					new ReflectionClass(BuilderMethodDoubleParent1::class),
					[],
					new MethodSource(
						new ReflectionMethod(BuilderMethodDoubleTrait1::class, 'a'),
					),
				),
			],
			new ClassSource(new ReflectionClass(BuilderMethodDoubleTrait1::class)),
		);
		self::assertEquals(
			$trait1,
			$builder->build(new ReflectionClass(BuilderMethodDoubleTrait1::class)),
		);
		self::assertNotEquals($trait1InContextOfClass, $trait1);

		$parent1 = new HierarchyClassStructure(
			null,
			[],
			[
				$trait1InContextOfClass,
			],
			[],
			[],
			[
				new MethodStructure(
					new ReflectionClass(BuilderMethodDoubleParent1::class),
					[],
					new MethodSource(
						new ReflectionMethod(BuilderMethodDoubleParent1::class, 'b'),
					),
				),
			],
			new ClassSource(new ReflectionClass(BuilderMethodDoubleParent1::class)),
		);
		self::assertEquals(
			$parent1,
			$builder->build(new ReflectionClass(BuilderMethodDoubleParent1::class)),
		);

		$trait2 = new HierarchyClassStructure(
			null,
			[],
			[],
			[],
			[],
			[
				new MethodStructure(
					new ReflectionClass(BuilderMethodDoubleTrait2::class),
					[],
					new MethodSource(
						new ReflectionMethod(BuilderMethodDoubleTrait2::class, 'c'),
					),
				),
			],
			new ClassSource(new ReflectionClass(BuilderMethodDoubleTrait2::class)),
		);
		$trait2InContextOfClass = new HierarchyClassStructure(
			null,
			[],
			[],
			[],
			[],
			[
				new MethodStructure(
					$class,
					[],
					new MethodSource(
						new ReflectionMethod(BuilderMethodDoubleTrait2::class, 'c'),
					),
				),
			],
			new ClassSource(new ReflectionClass(BuilderMethodDoubleTrait2::class)),
		);
		self::assertEquals(
			$trait2,
			$builder->build(new ReflectionClass(BuilderMethodDoubleTrait2::class)),
		);
		self::assertNotEquals($trait2InContextOfClass, $trait2);

		self::assertEquals(
			new HierarchyClassStructure(
				$parent1,
				[],
				[
					$trait2InContextOfClass,
				],
				[],
				[],
				[
					new MethodStructure(
						$class,
						[],
						new MethodSource(
							new ReflectionMethod(BuilderMethodDouble::class, 'd1'),
						),
					),
					new MethodStructure(
						$class,
						[
							new ParameterStructure(new ParameterSource(new ReflectionParameter(
								[BuilderMethodDouble::class, 'd2'],
								'a',
							))),
							new ParameterStructure(new ParameterSource(new ReflectionParameter(
								[BuilderMethodDouble::class, 'd2'],
								'b',
							))),
						],
						new MethodSource(
							new ReflectionMethod(BuilderMethodDouble::class, 'd2'),
						),
					),
				],
				new ClassSource($class),
			),
			$structure,
		);
	}

}
