<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Structure;

use Orisai\ReflectionMeta\Structure\MethodStructure;
use Orisai\ReflectionMeta\Structure\ParameterStructure;
use Orisai\SourceMap\MethodSource;
use Orisai\SourceMap\ParameterSource;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use ReflectionParameter;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Method\MethodStructureDouble;

final class MethodStructureTest extends TestCase
{

	public function testBase(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/method-structure.php';

		$reflector = new ReflectionMethod(MethodStructureDouble::class, 'a');

		$parameters = [];
		$source = new MethodSource($reflector);

		$method = new MethodStructure($reflector, $source, $parameters);

		self::assertSame($reflector, $method->getContextReflector());
		self::assertSame($source, $method->getSource());
		self::assertSame($parameters, $method->getParameters());
	}

	public function testExtra(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/method-structure.php';

		$reflector = new ReflectionMethod(MethodStructureDouble::class, 'a');

		$parameters = [
			new ParameterStructure(
				new ReflectionParameter([MethodStructureDouble::class, 'a'], 'b'),
				new ParameterSource(
					new ReflectionParameter([MethodStructureDouble::class, 'a'], 'b'),
				),
			),
			new ParameterStructure(
				new ReflectionParameter([MethodStructureDouble::class, 'a'], 'c'),
				new ParameterSource(
					new ReflectionParameter([MethodStructureDouble::class, 'a'], 'c'),
				),
			),
		];
		$source = new MethodSource($reflector);

		$method = new MethodStructure($reflector, $source, $parameters);

		self::assertSame($reflector, $method->getContextReflector());
		self::assertSame($source, $method->getSource());
		self::assertSame($parameters, $method->getParameters());
	}

}
