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
	 * @param class-string<T>         $attributeClass
	 * @return list<T>
	 */
	public function readClass(ReflectionClass $class, string $attributeClass): array;

	/**
	 * @template T of object
	 * @param class-string<T> $attributeClass
	 * @return list<T>
	 */
	public function readConstant(ReflectionClassConstant $constant, string $attributeClass): array;

	/**
	 * @template T of object
	 * @param class-string<T> $attributeClass
	 * @return list<T>
	 */
	public function readProperty(ReflectionProperty $property, string $attributeClass): array;

	/**
	 * @template T of object
	 * @param class-string<T> $attributeClass
	 * @return list<T>
	 */
	public function readMethod(ReflectionMethod $method, string $attributeClass): array;

	/**
	 * @template T of object
	 * @param class-string<T> $attributeClass
	 * @return list<T>
	 */
	public function readParameter(ReflectionParameter $parameter, string $attributeClass): array;

}
