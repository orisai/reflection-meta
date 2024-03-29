<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\ReflectionMeta\Finder\ConstantDeclaratorFinder;
use Orisai\ReflectionMeta\Finder\MethodDeclaratorFinder;
use Orisai\ReflectionMeta\Finder\PropertyDeclaratorFinder;
use Orisai\SourceMap\ClassConstantSource;
use Orisai\SourceMap\ClassSource;
use Orisai\SourceMap\MethodSource;
use Orisai\SourceMap\ParameterSource;
use Orisai\SourceMap\PropertySource;
use ReflectionClass;
use ReflectionMethod;

final class StructureBuilder
{

	/**
	 * @param ReflectionClass<object> $class
	 */
	public static function build(ReflectionClass $class): HierarchyClassStructure
	{
		return self::createClassStructure($class, $class);
	}

	/**
	 * @param ReflectionClass<object> $declaringClass
	 * @param ReflectionClass<object> $contextClass
	 */
	private static function createClassStructure(
		ReflectionClass $declaringClass,
		ReflectionClass $contextClass
	): HierarchyClassStructure
	{
		return new HierarchyClassStructure(
			$contextClass,
			self::createParentStructure($declaringClass),
			self::createInterfacesStructure($declaringClass, $contextClass),
			self::createTraitsStructure($declaringClass, $contextClass),
			self::createConstantsStructure($declaringClass, $contextClass),
			self::createPropertiesStructure($declaringClass, $contextClass),
			self::createMethodsStructure($declaringClass, $contextClass),
			new ClassSource($declaringClass),
		);
	}

	/**
	 * @param ReflectionClass<object> $class
	 */
	private static function createParentStructure(ReflectionClass $class): ?HierarchyClassStructure
	{
		$parentClass = $class->getParentClass();

		if ($parentClass === false) {
			return null;
		}

		return self::createClassStructure($parentClass, $parentClass);
	}

	/**
	 * @param ReflectionClass<object> $declaringClass
	 * @param ReflectionClass<object> $contextClass
	 * @return list<HierarchyClassStructure>
	 */
	private static function createInterfacesStructure(
		ReflectionClass $declaringClass,
		ReflectionClass $contextClass
	): array
	{
		$interfaces = [];
		foreach ($declaringClass->getInterfaces() as $interface) {
			$interfaces[] = self::createClassStructure($interface, $contextClass);
		}

		return $interfaces;
	}

	/**
	 * @param ReflectionClass<object> $declaringClass
	 * @param ReflectionClass<object> $contextClass
	 * @return list<HierarchyClassStructure>
	 */
	private static function createTraitsStructure(
		ReflectionClass $declaringClass,
		ReflectionClass $contextClass
	): array
	{
		$traits = [];
		foreach ($declaringClass->getTraits() as $trait) {
			$traits[] = self::createClassStructure($trait, $contextClass);
		}

		return $traits;
	}

	/**
	 * @param ReflectionClass<object> $declaringClass
	 * @param ReflectionClass<object> $contextClass
	 * @return list<ConstantStructure>
	 */
	private static function createConstantsStructure(
		ReflectionClass $declaringClass,
		ReflectionClass $contextClass
	): array
	{
		$constants = [];
		foreach ($declaringClass->getReflectionConstants() as $constant) {
			if ($constant->getDeclaringClass()->getName() !== $declaringClass->getName()) {
				// We don't want parent public and protected constants, they are collected individually

				continue;
			}

			$constants[] = new ConstantStructure(
				$contextClass->getReflectionConstant($constant->getName()),
				new ClassConstantSource($constant),
				ConstantDeclaratorFinder::getDeclaringTraits($constant),
			);
		}

		return $constants;
	}

	/**
	 * @param ReflectionClass<object> $declaringClass
	 * @param ReflectionClass<object> $contextClass
	 * @return list<PropertyStructure>
	 */
	private static function createPropertiesStructure(
		ReflectionClass $declaringClass,
		ReflectionClass $contextClass
	): array
	{
		$properties = [];
		foreach ($declaringClass->getProperties() as $property) {
			// We don't want parent public and protected properties, they are collected individually
			if ($property->getDeclaringClass()->getName() !== $declaringClass->getName()) {
				continue;
			}

			$properties[] = new PropertyStructure(
				$contextClass->getProperty($property->getName()),
				new PropertySource($property),
				// We have to keep duplicates because they can be sourced in different code paths
				PropertyDeclaratorFinder::getDeclaringTraits($property),
			);
		}

		return $properties;
	}

	/**
	 * @param ReflectionClass<object> $declaringClass
	 * @param ReflectionClass<object> $contextClass
	 * @return list<MethodStructure>
	 */
	private static function createMethodsStructure(
		ReflectionClass $declaringClass,
		ReflectionClass $contextClass
	): array
	{
		$methods = [];
		foreach ($declaringClass->getMethods() as $method) {
			// We don't want parent public and protected methods, they are collected individually
			if ($method->getDeclaringClass()->getName() !== $declaringClass->getName()) {
				/** @infection-ignore-all break would work too, because methods are sorted by PHP from class through parent to trait */
				continue;
			}

			$declaringTrait = MethodDeclaratorFinder::getDeclaringTrait($method);

			// Only one trait can define method, skipping current one is safe
			if ($declaringTrait !== null) {
				/** @infection-ignore-all break would work too, because methods are sorted by PHP from class through parent to trait */
				continue;
			}

			$contextMethod = $contextClass->getMethod($method->getName());
			$methods[] = new MethodStructure(
				$contextMethod,
				new MethodSource($method),
				self::createParametersStructure($method, $contextMethod),
			);
		}

		return $methods;
	}

	/**
	 * @return list<ParameterStructure>
	 */
	private static function createParametersStructure(
		ReflectionMethod $declaringMethod,
		ReflectionMethod $contextMethod
	): array
	{
		$parameters = [];
		foreach ($declaringMethod->getParameters() as $i => $parameter) {
			$parameters[] = new ParameterStructure(
				$contextMethod->getParameters()[$i],
				new ParameterSource($parameter),
			);
		}

		return $parameters;
	}

}
