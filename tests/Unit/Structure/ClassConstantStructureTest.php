<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\ClassConstantStructure;
use Orisai\SourceMap\ClassConstantSource;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionClassConstant;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ClassConstant\ClassConstantStructureDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ClassConstantPHP82\ClassConstantStructurePHP82Double;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ClassConstantPHP82\PropertyStructurePHP82DoubleTrait1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ClassConstantPHP82\PropertyStructurePHP82DoubleTrait2;
use const PHP_VERSION_ID;

final class ClassConstantStructureTest extends TestCase
{

	public function testBase(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/class-constant-structure.php';

		$reflector = new ReflectionClassConstant(ClassConstantStructureDouble::class, 'A');

		$contextClass = $reflector->getDeclaringClass();
		$source = new ClassConstantSource($reflector);
		$duplicators = [];

		$constant = new ClassConstantStructure($contextClass, $source, $duplicators);
		self::assertSame($contextClass, $constant->getContextClass());
		self::assertSame($source, $constant->getSource());
		self::assertSame($duplicators, $constant->getDuplicators());
	}

	public function testDuplicators(): void
	{
		if (PHP_VERSION_ID < 8_02_00) {
			self::markTestSkipped('Attributes are supported on PHP 8.2+');
		}

		require_once __DIR__ . '/../../Doubles/Structure/class-constant-structure-php8.2.php';

		$reflector = new ReflectionClassConstant(ClassConstantStructurePHP82Double::class, 'A');

		$contextClass = $reflector->getDeclaringClass();
		$source = new ClassConstantSource($reflector);
		$duplicators = [
			new ReflectionClass(PropertyStructurePHP82DoubleTrait1::class),
			new ReflectionClass(PropertyStructurePHP82DoubleTrait2::class),
		];

		$constant = new ClassConstantStructure($contextClass, $source, $duplicators);
		self::assertSame($duplicators, $constant->getDuplicators());
	}

}
