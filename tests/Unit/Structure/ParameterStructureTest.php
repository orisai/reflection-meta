<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\ParameterStructure;
use Orisai\SourceMap\ParameterSource;
use PHPUnit\Framework\TestCase;
use ReflectionParameter;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Method\MethodStructureDouble;

final class ParameterStructureTest extends TestCase
{

	public function test(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/method-structure.php';

		$reflector = new ReflectionParameter(
			[MethodStructureDouble::class, 'a'],
			'b',
		);
		$source = new ParameterSource($reflector);

		$parameter = new ParameterStructure($reflector, $source);
		self::assertSame($reflector, $parameter->getContextReflector());
		self::assertSame($source, $parameter->getSource());
	}

}
