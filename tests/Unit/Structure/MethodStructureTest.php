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

		$contextClass = $reflector->getDeclaringClass();
		$parameters = [];
		$source = new MethodSource($reflector);

		$method = new MethodStructure($contextClass, $parameters, $source);

		self::assertSame($contextClass, $method->getContextClass());
		self::assertSame($parameters, $method->getParameters());
		self::assertSame($source, $method->getSource());
	}

	public function testExtra(): void
	{
		require_once __DIR__ . '/../../Doubles/Structure/method-structure.php';

		$reflector = new ReflectionMethod(MethodStructureDouble::class, 'a');

		$contextClass = $reflector->getDeclaringClass();
		$parameters = [
			new ParameterStructure(new ParameterSource(
				new ReflectionParameter([MethodStructureDouble::class, 'a'], 'b'),
			)),
			new ParameterStructure(new ParameterSource(
				new ReflectionParameter([MethodStructureDouble::class, 'a'], 'c'),
			)),
		];
		$source = new MethodSource($reflector);

		$method = new MethodStructure($contextClass, $parameters, $source);

		self::assertSame($contextClass, $method->getContextClass());
		self::assertSame($parameters, $method->getParameters());
		self::assertSame($source, $method->getSource());
	}

}
