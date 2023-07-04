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
	 * @template T of object
	 * @param ReflectionClass<object> $class
	 * @param class-string<T>         $definitionClass
	 * @return list<T>
	 */
	public function readClass(ReflectionClass $class, string $definitionClass): array;

	/**
	 * @template T of object
	 * @param class-string<T> $definitionClass
	 * @return list<T>
	 */
	public function readConstant(ReflectionClassConstant $constant, string $definitionClass): array;

	/**
	 * @template T of object
	 * @param class-string<T> $definitionClass
	 * @return list<T>
	 */
	public function readProperty(ReflectionProperty $property, string $definitionClass): array;

	/**
	 * @template T of object
	 * @param class-string<T> $definitionClass
	 * @return list<T>
	 */
	public function readMethod(ReflectionMethod $method, string $definitionClass): array;

	/**
	 * @template T of object
	 * @param class-string<T> $definitionClass
	 * @return list<T>
	 */
	public function readParameter(ReflectionParameter $parameter, string $definitionClass): array;

}
