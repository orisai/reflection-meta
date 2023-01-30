<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\ClassStructure;
use Orisai\SourceMap\ClassSource;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;

final class ClassStructureTest extends TestCase
{

	public function test(): void
	{
		$reflector = new ReflectionClass(stdClass::class);
		$source = new ClassSource($reflector);
		$structure = new ClassStructure($reflector, $source);

		self::assertSame($reflector, $structure->getContextClass());
		self::assertSame($source, $structure->getSource());
	}

}
