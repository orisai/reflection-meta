<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\ConstantStructure;
use Orisai\ReflectionMeta\Structure\HierarchyClassStructure;
use Orisai\ReflectionMeta\Structure\MethodStructure;
use Orisai\ReflectionMeta\Structure\ParameterStructure;
use Orisai\ReflectionMeta\Structure\PropertyStructure;
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
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ConstantsPHP82\BuilderConstantPHP82Double;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ConstantsPHP82\BuilderConstantPHP82DoubleInterface1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ConstantsPHP82\BuilderConstantPHP82DoubleParent1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ConstantsPHP82\BuilderConstantPHP82DoubleTrait1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Interfaces\BuilderInterfaceDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Interfaces\BuilderInterfaceDoubleInterface1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Interfaces\BuilderInterfaceDoubleInterface2;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Interfaces\BuilderInterfaceDoubleInterface3;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Methods\BuilderMethodDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Methods\BuilderMethodDoubleInterface2;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Methods\BuilderMethodDoubleParent1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Methods\BuilderMethodDoubleParent2;
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
use const PHP_VERSION_ID;

final class StructureBuilderTest extends TestCase
{

	public function testBase(): void
	{
		$class = new ReflectionClass(stdClass::class);
		$structure = StructureBuilder::build($class);

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

		$class = new ReflectionClass(BuilderParentDouble::class);
		$structure = StructureBuilder::build($class);

		$parent1 = new HierarchyClassStructure(
			new ReflectionClass(BuilderParentDoubleParent1::class),
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
			StructureBuilder::build(new ReflectionClass(BuilderParentDoubleParent1::class)),
		);

		$parent2 = new HierarchyClassStructure(
			new ReflectionClass(BuilderParentDoubleParent2::class),
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
			StructureBuilder::build(new ReflectionClass(BuilderParentDoubleParent2::class)),
		);

		self::assertEquals(
			new HierarchyClassStructure(
				$class,
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

		$class = new ReflectionClass(BuilderInterfaceDouble::class);
		$structure = StructureBuilder::build($class);

		$i1 = new HierarchyClassStructure(
			$class,
			null,
			[],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderInterfaceDoubleInterface1::class)),
		);
		self::assertNotEquals(
			$i1,
			StructureBuilder::build(new ReflectionClass(BuilderInterfaceDoubleInterface1::class)),
		);

		$i2 = new HierarchyClassStructure(
			$class,
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
		self::assertNotEquals(
			$i2,
			StructureBuilder::build(new ReflectionClass(BuilderInterfaceDoubleInterface2::class)),
		);

		$i3 = new HierarchyClassStructure(
			$class,
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
		self::assertNotEquals(
			$i3,
			StructureBuilder::build(new ReflectionClass(BuilderInterfaceDoubleInterface3::class)),
		);

		self::assertEquals(
			new HierarchyClassStructure(
				$class,
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

		$class = new ReflectionClass(BuilderTraitDouble::class);
		$structure = StructureBuilder::build($class);

		$t1 = new HierarchyClassStructure(
			$class,
			null,
			[],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderTraitDoubleTrait1::class)),
		);
		self::assertNotEquals(
			$t1,
			StructureBuilder::build(new ReflectionClass(BuilderTraitDoubleTrait1::class)),
		);

		$t2 = new HierarchyClassStructure(
			$class,
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
		self::assertNotEquals(
			$t2,
			StructureBuilder::build(new ReflectionClass(BuilderTraitDoubleTrait2::class)),
		);

		$t3 = new HierarchyClassStructure(
			$class,
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
		self::assertNotEquals(
			$t3,
			StructureBuilder::build(new ReflectionClass(BuilderTraitDoubleTrait3::class)),
		);

		self::assertEquals(
			new HierarchyClassStructure(
				$class,
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

		$class = new ReflectionClass(BuilderParentTraitDouble::class);
		$structure = StructureBuilder::build($class);

		$trait1 = new HierarchyClassStructure(
			new ReflectionClass(BuilderParentTraitDoubleParent1::class),
			null,
			[],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderParentTraitDoubleTrait1::class)),
		);
		self::assertNotEquals(
			$trait1,
			StructureBuilder::build(new ReflectionClass(BuilderParentTraitDoubleTrait1::class)),
		);

		$parent1 = new HierarchyClassStructure(
			new ReflectionClass(BuilderParentTraitDoubleParent1::class),
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
			StructureBuilder::build(new ReflectionClass(BuilderParentTraitDoubleParent1::class)),
		);

		self::assertEquals(
			new HierarchyClassStructure(
				$class,
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

		$class = new ReflectionClass(BuilderParentInterfaceDouble::class);
		$structure = StructureBuilder::build($class);

		$interface1InContextOfParent = new HierarchyClassStructure(
			new ReflectionClass(BuilderParentInterfaceDoubleParent1::class),
			null,
			[],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderParentInterfaceDoubleInterface1::class)),
		);
		$interface1InContextOfChild = new HierarchyClassStructure(
			$class,
			null,
			[],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderParentInterfaceDoubleInterface1::class)),
		);

		$parent1 = new HierarchyClassStructure(
			new ReflectionClass(BuilderParentInterfaceDoubleParent1::class),
			null,
			[
				$interface1InContextOfParent,
			],
			[],
			[],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderParentInterfaceDoubleParent1::class)),
		);
		self::assertEquals(
			$parent1,
			StructureBuilder::build(new ReflectionClass(BuilderParentInterfaceDoubleParent1::class)),
		);

		self::assertEquals(
			new HierarchyClassStructure(
				$class,
				$parent1,
				[
					$interface1InContextOfChild,
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

		$class = new ReflectionClass(BuilderConstantDouble::class);
		$structure = StructureBuilder::build($class);

		$interface1 = new HierarchyClassStructure(
			$class,
			null,
			[],
			[],
			[
				new ConstantStructure(
					$class->getReflectionConstant('A'),
					new ClassConstantSource(
						new ReflectionClassConstant(BuilderConstantDoubleInterface1::class, 'A'),
					),
					[],
				),
			],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderConstantDoubleInterface1::class)),
		);
		self::assertNotEquals(
			$interface1,
			StructureBuilder::build(new ReflectionClass(BuilderConstantDoubleInterface1::class)),
		);

		$parent1 = new HierarchyClassStructure(
			$parent1Class = new ReflectionClass(BuilderConstantDoubleParent1::class),
			null,
			[],
			[],
			[
				new ConstantStructure(
					$parent1Class->getReflectionConstant('B'),
					new ClassConstantSource(
						new ReflectionClassConstant(BuilderConstantDoubleParent1::class, 'B'),
					),
					[],
				),
			],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderConstantDoubleParent1::class)),
		);
		self::assertEquals(
			$parent1,
			StructureBuilder::build(new ReflectionClass(BuilderConstantDoubleParent1::class)),
		);

		self::assertEquals(
			new HierarchyClassStructure(
				$class,
				$parent1,
				[
					$interface1,
				],
				[],
				[
					new ConstantStructure(
						$class->getReflectionConstant('C1'),
						new ClassConstantSource(
							new ReflectionClassConstant(BuilderConstantDouble::class, 'C1'),
						),
						[],
					),
					new ConstantStructure(
						$class->getReflectionConstant('C2'),
						new ClassConstantSource(
							new ReflectionClassConstant(BuilderConstantDouble::class, 'C2'),
						),
						[],
					),
				],
				[],
				[],
				new ClassSource($class),
			),
			$structure,
		);
	}

	public function testConstantsPHP82(): void
	{
		if (PHP_VERSION_ID < 8_02_00) {
			self::markTestSkipped('Attributes are supported on PHP 8.2+');
		}

		require_once __DIR__ . '/../../Doubles/Structure/builder-constant-structure-php8.2.php';

		$class = new ReflectionClass(BuilderConstantPHP82Double::class);
		$structure = StructureBuilder::build($class);

		$interface1 = new HierarchyClassStructure(
			$class,
			null,
			[],
			[],
			[
				new ConstantStructure(
					$class->getReflectionConstant('A'),
					new ClassConstantSource(
						new ReflectionClassConstant(BuilderConstantPHP82DoubleInterface1::class, 'A'),
					),
					[],
				),
			],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderConstantPHP82DoubleInterface1::class)),
		);
		self::assertNotEquals(
			$interface1,
			StructureBuilder::build(new ReflectionClass(BuilderConstantPHP82DoubleInterface1::class)),
		);

		$parent1 = new HierarchyClassStructure(
			$parent1Class = new ReflectionClass(BuilderConstantPHP82DoubleParent1::class),
			null,
			[],
			[],
			[
				new ConstantStructure(
					$parent1Class->getReflectionConstant('B'),
					new ClassConstantSource(
						new ReflectionClassConstant(BuilderConstantPHP82DoubleParent1::class, 'B'),
					),
					[],
				),
			],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderConstantPHP82DoubleParent1::class)),
		);
		self::assertEquals(
			$parent1,
			StructureBuilder::build(new ReflectionClass(BuilderConstantPHP82DoubleParent1::class)),
		);

		$trait1 = new HierarchyClassStructure(
			$trait1Class = new ReflectionClass(BuilderConstantPHP82DoubleTrait1::class),
			null,
			[],
			[],
			[
				new ConstantStructure(
					$trait1Class->getReflectionConstant('C'),
					new ClassConstantSource(
						new ReflectionClassConstant(BuilderConstantPHP82DoubleTrait1::class, 'C'),
					),
					[],
				),
			],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderConstantPHP82DoubleTrait1::class)),
		);
		$trait1inContextOfClass = new HierarchyClassStructure(
			new ReflectionClass(BuilderConstantPHP82Double::class),
			null,
			[],
			[],
			[
				new ConstantStructure(
					$class->getReflectionConstant('C'),
					new ClassConstantSource(
						new ReflectionClassConstant(BuilderConstantPHP82DoubleTrait1::class, 'C'),
					),
					[],
				),
			],
			[],
			[],
			new ClassSource(new ReflectionClass(BuilderConstantPHP82DoubleTrait1::class)),
		);
		self::assertEquals(
			$trait1,
			StructureBuilder::build(new ReflectionClass(BuilderConstantPHP82DoubleTrait1::class)),
		);
		self::assertNotEquals($trait1inContextOfClass, $trait1);

		self::assertEquals(
			new HierarchyClassStructure(
				$class,
				$parent1,
				[
					$interface1,
				],
				[
					$trait1inContextOfClass,
				],
				[
					new ConstantStructure(
						$class->getReflectionConstant('D1'),
						new ClassConstantSource(
							new ReflectionClassConstant(BuilderConstantPHP82Double::class, 'D1'),
						),
						[],
					),
					new ConstantStructure(
						$class->getReflectionConstant('D2'),
						new ClassConstantSource(
							new ReflectionClassConstant(BuilderConstantPHP82Double::class, 'D2'),
						),
						[],
					),
					new ConstantStructure(
						$class->getReflectionConstant('C'),
						new ClassConstantSource(
							new ReflectionClassConstant(BuilderConstantPHP82Double::class, 'C'),
						),
						[
							new ReflectionClass(BuilderConstantPHP82DoubleTrait1::class),
						],
					),
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

		$class = new ReflectionClass(BuilderPropertyDouble::class);
		$structure = StructureBuilder::build($class);

		$trait1 = new HierarchyClassStructure(
			new ReflectionClass(BuilderPropertyDoubleTrait1::class),
			null,
			[],
			[],
			[],
			[
				new PropertyStructure(
					new ReflectionProperty(BuilderPropertyDoubleTrait1::class, 'a'),
					new PropertySource(
						new ReflectionProperty(BuilderPropertyDoubleTrait1::class, 'a'),
					),
					[],
				),
			],
			[],
			new ClassSource(new ReflectionClass(BuilderPropertyDoubleTrait1::class)),
		);
		$trait1InContextOfClass = new HierarchyClassStructure(
			new ReflectionClass(BuilderPropertyDoubleParent1::class),
			null,
			[],
			[],
			[],
			[
				new PropertyStructure(
					new ReflectionProperty(BuilderPropertyDoubleParent1::class, 'a'),
					new PropertySource(
						new ReflectionProperty(BuilderPropertyDoubleTrait1::class, 'a'),
					),
					[],
				),
			],
			[],
			new ClassSource(new ReflectionClass(BuilderPropertyDoubleTrait1::class)),
		);
		self::assertEquals(
			$trait1,
			StructureBuilder::build(new ReflectionClass(BuilderPropertyDoubleTrait1::class)),
		);
		self::assertNotEquals($trait1InContextOfClass, $trait1);

		$parent1 = new HierarchyClassStructure(
			new ReflectionClass(BuilderPropertyDoubleParent1::class),
			null,
			[],
			[
				$trait1InContextOfClass,
			],
			[],
			[
				new PropertyStructure(
					new ReflectionProperty(BuilderPropertyDoubleParent1::class, 'b'),
					new PropertySource(
						new ReflectionProperty(BuilderPropertyDoubleParent1::class, 'b'),
					),
					[],
				),
				new PropertyStructure(
					new ReflectionProperty(BuilderPropertyDoubleParent1::class, 'a'),
					new PropertySource(
						new ReflectionProperty(BuilderPropertyDoubleParent1::class, 'a'),
					),
					[
						new ReflectionClass(BuilderPropertyDoubleTrait1::class),
					],
				),
			],
			[],
			new ClassSource(new ReflectionClass(BuilderPropertyDoubleParent1::class)),
		);
		self::assertEquals(
			$parent1,
			StructureBuilder::build(new ReflectionClass(BuilderPropertyDoubleParent1::class)),
		);

		$trait2 = new HierarchyClassStructure(
			new ReflectionClass(BuilderPropertyDoubleTrait2::class),
			null,
			[],
			[],
			[],
			[
				new PropertyStructure(
					new ReflectionProperty(BuilderPropertyDoubleTrait2::class, 'c'),
					new PropertySource(
						new ReflectionProperty(BuilderPropertyDoubleTrait2::class, 'c'),
					),
					[],
				),
			],
			[],
			new ClassSource(new ReflectionClass(BuilderPropertyDoubleTrait2::class)),
		);
		$trait2InContextOfClass = new HierarchyClassStructure(
			$class,
			null,
			[],
			[],
			[],
			[
				new PropertyStructure(
					$class->getProperty('c'),
					new PropertySource(
						new ReflectionProperty(BuilderPropertyDoubleTrait2::class, 'c'),
					),
					[],
				),
			],
			[],
			new ClassSource(new ReflectionClass(BuilderPropertyDoubleTrait2::class)),
		);
		self::assertEquals(
			$trait2,
			StructureBuilder::build(new ReflectionClass(BuilderPropertyDoubleTrait2::class)),
		);
		self::assertNotEquals($trait2InContextOfClass, $trait2);

		self::assertEquals(
			new HierarchyClassStructure(
				$class,
				$parent1,
				[],
				[
					$trait2InContextOfClass,
				],
				[],
				[
					new PropertyStructure(
						$class->getProperty('d1'),
						new PropertySource(
							new ReflectionProperty(BuilderPropertyDouble::class, 'd1'),
						),
						[],
					),
					new PropertyStructure(
						$class->getProperty('d2'),
						new PropertySource(
							new ReflectionProperty(BuilderPropertyDouble::class, 'd2'),
						),
						[],
					),
					new PropertyStructure(
						$class->getProperty('c'),
						new PropertySource(
							new ReflectionProperty(BuilderPropertyDouble::class, 'c'),
						),
						[
							new ReflectionClass(BuilderPropertyDoubleTrait2::class),
						],
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

		$class = new ReflectionClass(BuilderMethodDouble::class);
		$structure = StructureBuilder::build($class);

		$trait1 = new HierarchyClassStructure(
			new ReflectionClass(BuilderMethodDoubleTrait1::class),
			null,
			[],
			[],
			[],
			[],
			[
				new MethodStructure(
					new ReflectionMethod(BuilderMethodDoubleTrait1::class, 'a'),
					new MethodSource(
						new ReflectionMethod(BuilderMethodDoubleTrait1::class, 'a'),
					),
					[],
				),
			],
			new ClassSource(new ReflectionClass(BuilderMethodDoubleTrait1::class)),
		);
		$trait1InContextOfClass = new HierarchyClassStructure(
			new ReflectionClass(BuilderMethodDoubleParent1::class),
			null,
			[],
			[],
			[],
			[],
			[
				new MethodStructure(
					new ReflectionMethod(BuilderMethodDoubleParent1::class, 'a'),
					new MethodSource(
						new ReflectionMethod(BuilderMethodDoubleTrait1::class, 'a'),
					),
					[],
				),
			],
			new ClassSource(new ReflectionClass(BuilderMethodDoubleTrait1::class)),
		);
		self::assertEquals(
			$trait1,
			StructureBuilder::build(new ReflectionClass(BuilderMethodDoubleTrait1::class)),
		);
		self::assertNotEquals($trait1InContextOfClass, $trait1);

		$parent1 = new HierarchyClassStructure(
			new ReflectionClass(BuilderMethodDoubleParent1::class),
			new HierarchyClassStructure(
				new ReflectionClass(BuilderMethodDoubleParent2::class),
				null,
				[],
				[],
				[],
				[],
				[
					new MethodStructure(
						new ReflectionMethod(BuilderMethodDoubleParent2::class, 'e'),
						new MethodSource(
							new ReflectionMethod(BuilderMethodDoubleParent2::class, 'e'),
						),
						[],
					),
				],
				new ClassSource(new ReflectionClass(BuilderMethodDoubleParent2::class)),
			),
			[],
			[
				$trait1InContextOfClass,
			],
			[],
			[],
			[
				new MethodStructure(
					new ReflectionMethod(BuilderMethodDoubleParent1::class, 'b'),
					new MethodSource(
						new ReflectionMethod(BuilderMethodDoubleParent1::class, 'b'),
					),
					[],
				),
			],
			new ClassSource(new ReflectionClass(BuilderMethodDoubleParent1::class)),
		);
		self::assertEquals(
			$parent1,
			StructureBuilder::build(new ReflectionClass(BuilderMethodDoubleParent1::class)),
		);

		$trait2 = new HierarchyClassStructure(
			new ReflectionClass(BuilderMethodDoubleTrait2::class),
			null,
			[],
			[],
			[],
			[],
			[
				new MethodStructure(
					new ReflectionMethod(BuilderMethodDoubleTrait2::class, 'c'),
					new MethodSource(
						new ReflectionMethod(BuilderMethodDoubleTrait2::class, 'c'),
					),
					[],
				),
			],
			new ClassSource(new ReflectionClass(BuilderMethodDoubleTrait2::class)),
		);
		$trait2InContextOfClass = new HierarchyClassStructure(
			$class,
			null,
			[],
			[],
			[],
			[],
			[
				new MethodStructure(
					$class->getMethod('c'),
					new MethodSource(
						new ReflectionMethod(BuilderMethodDoubleTrait2::class, 'c'),
					),
					[],
				),
			],
			new ClassSource(new ReflectionClass(BuilderMethodDoubleTrait2::class)),
		);
		self::assertEquals(
			$trait2,
			StructureBuilder::build(new ReflectionClass(BuilderMethodDoubleTrait2::class)),
		);
		self::assertNotEquals($trait2InContextOfClass, $trait2);

		self::assertEquals(
			new HierarchyClassStructure(
				$class,
				$parent1,
				[
					new HierarchyClassStructure(
						$class,
						null,
						[],
						[],
						[],
						[],
						[
							new MethodStructure(
								$class->getMethod('c'),
								new MethodSource(
									new ReflectionMethod(BuilderMethodDoubleInterface2::class, 'c'),
								),
								[],
							),
						],
						new ClassSource(new ReflectionClass(BuilderMethodDoubleInterface2::class)),
					),
				],
				[
					$trait2InContextOfClass,
				],
				[],
				[],
				[
					new MethodStructure(
						$class->getMethod('d1'),
						new MethodSource(
							new ReflectionMethod(BuilderMethodDouble::class, 'd1'),
						),
						[],
					),
					new MethodStructure(
						$class->getMethod('d2'),
						new MethodSource(
							new ReflectionMethod(BuilderMethodDouble::class, 'd2'),
						),
						[
							new ParameterStructure(
								new ReflectionParameter(
									[BuilderMethodDouble::class, 'd2'],
									'a',
								),
								new ParameterSource(new ReflectionParameter(
									[BuilderMethodDouble::class, 'd2'],
									'a',
								)),
							),
							new ParameterStructure(
								new ReflectionParameter(
									[BuilderMethodDouble::class, 'd2'],
									'b',
								),
								new ParameterSource(new ReflectionParameter(
									[BuilderMethodDouble::class, 'd2'],
									'b',
								)),
							),
						],
					),
				],
				new ClassSource($class),
			),
			$structure,
		);
	}

}
