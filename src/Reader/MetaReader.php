<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Reader;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

interface MetaReader
{

	/**
	 * @param ReflectionClass<object> $class
	 * @return list<object>
	 */
	public function readClass(ReflectionClass $class): array;

	/**
	 * @return list<object>
	 */
	public function readClassConstant(ReflectionClassConstant $constant): array;

	/**
	 * @return list<object>
	 */
	public function readProperty(ReflectionProperty $property): array;

	/**
	 * @return list<object>
	 */
	public function readMethod(ReflectionMethod $method): array;

	/**
	 * @return list<object>
	 */
	public function readParameter(ReflectionParameter $parameter): array;

}
