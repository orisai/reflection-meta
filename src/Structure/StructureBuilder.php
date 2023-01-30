<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

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
	public function build(ReflectionClass $class): HierarchyClassStructure
	{
		return $this->createClassStructure($class, $class);
	}

	/**
	 * @param ReflectionClass<object> $declaringClass
	 * @param ReflectionClass<object> $contextClass
	 */
	private function createClassStructure(
		ReflectionClass $declaringClass,
		ReflectionClass $contextClass
	): HierarchyClassStructure
	{
		return new HierarchyClassStructure(
			$contextClass,
			$this->createParentStructure($declaringClass),
			$this->createInterfacesStructure($declaringClass, $contextClass),
			$this->createTraitsStructure($declaringClass, $contextClass),
			$this->createClassConstantsStructure($declaringClass),
			$this->createPropertiesStructure($declaringClass, $contextClass),
			$this->createMethodsStructure($declaringClass, $contextClass),
			new ClassSource($declaringClass),
		);
	}

	/**
	 * @param ReflectionClass<object> $class
	 */
	private function createParentStructure(ReflectionClass $class): ?HierarchyClassStructure
	{
		$parentClass = $class->getParentClass();

		if ($parentClass === false) {
			return null;
		}

		return $this->createClassStructure($parentClass, $parentClass);
	}

	/**
	 * @param ReflectionClass<object> $declaringClass
	 * @param ReflectionClass<object> $contextClass
	 * @return list<HierarchyClassStructure>
	 */
	private function createInterfacesStructure(
		ReflectionClass $declaringClass,
		ReflectionClass $contextClass
	): array
	{
		$interfaces = [];
		foreach ($declaringClass->getInterfaces() as $interface) {
			$interfaces[] = $this->createClassStructure($interface, $contextClass);
		}

		return $interfaces;
	}

	/**
	 * @param ReflectionClass<object> $declaringClass
	 * @param ReflectionClass<object> $contextClass
	 * @return list<HierarchyClassStructure>
	 */
	private function createTraitsStructure(
		ReflectionClass $declaringClass,
		ReflectionClass $contextClass
	): array
	{
		$traits = [];
		foreach ($declaringClass->getTraits() as $trait) {
			$traits[] = $this->createClassStructure($trait, $contextClass);
		}

		return $traits;
	}

	/**
	 * @param ReflectionClass<object> $declaringClass
	 * @return list<ClassConstantStructure>
	 */
	private function createClassConstantsStructure(ReflectionClass $declaringClass): array
	{
		$constants = [];
		foreach ($declaringClass->getReflectionConstants() as $constant) {
			if ($constant->getDeclaringClass()->getName() !== $declaringClass->getName()) {
				// We don't want parent public and protected constants, they are collected individually

				continue;
			}

			$constants[] = new ClassConstantStructure(
				new ClassConstantSource($constant),
			);
		}

		return $constants;
	}

	/**
	 * @param ReflectionClass<object> $declaringClass
	 * @param ReflectionClass<object> $contextClass
	 * @return list<PropertyWithDuplicatesStructure>
	 */
	private function createPropertiesStructure(
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

			$properties[] = new PropertyWithDuplicatesStructure(
				$contextClass,
				// We have to keep duplicates because they can be sourced in different code paths
				PropertyDeclaratorFinder::getDeclaringTraits($property),
				new PropertySource($property),
			);
		}

		return $properties;
	}

	/**
	 * @param ReflectionClass<object> $declaringClass
	 * @param ReflectionClass<object> $contextClass
	 * @return list<MethodStructure>
	 */
	private function createMethodsStructure(
		ReflectionClass $declaringClass,
		ReflectionClass $contextClass
	): array
	{
		$methods = [];
		foreach ($declaringClass->getMethods() as $method) {
			// We don't want parent public and protected methods, they are collected individually
			if ($method->getDeclaringClass()->getName() !== $declaringClass->getName()) {
				continue;
			}

			$declaringTrait = MethodDeclaratorFinder::getDeclaringTrait($method);

			// Only one trait can define method, skipping current one is safe
			if ($declaringTrait !== null) {
				continue;
			}

			$methods[] = new MethodStructure(
				$contextClass,
				$this->createParametersStructure($method),
				new MethodSource($method),
			);
		}

		return $methods;
	}

	/**
	 * @return list<ParameterStructure>
	 */
	private function createParametersStructure(ReflectionMethod $method): array
	{
		$parameters = [];
		foreach ($method->getParameters() as $parameter) {
			$parameters[] = new ParameterStructure(
				new ParameterSource($parameter),
			);
		}

		return $parameters;
	}

}
