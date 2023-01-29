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
		$source = new ClassSource(new ReflectionClass(stdClass::class));
		$structure = new ClassStructure($source);

		self::assertSame($source, $structure->getSource());
	}

}
