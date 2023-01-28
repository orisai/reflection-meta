<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\ClassConstantStructure;
use Orisai\SourceMap\ClassConstantSource;
use PHPUnit\Framework\TestCase;
use ReflectionClassConstant;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\ClassConstant\ClassConstantStructureDouble;

final class ClassConstantStructureTest extends TestCase
{

	public function test(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/class-constant-structure.php';

		$reflector = new ReflectionClassConstant(ClassConstantStructureDouble::class, 'A');
		$source = new ClassConstantSource($reflector);

		$constant = new ClassConstantStructure($source);
		self::assertSame($source, $constant->getSource());
	}

}
