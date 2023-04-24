<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use function array_merge;

final class StructureFlattener
{

	public static function flatten(HierarchyClassStructure $classStructure): StructureList
	{
		$classes = self::removeDuplicateClasses(self::flattenClasses($classStructure));

		return new StructureList(
			self::unpackClasses($classes),
			self::unpackConstants($classes),
			self::removeDuplicateProperties(self::unpackProperties($classes)),
			self::unpackMethods($classes),
		);
	}

	/**
	 * @return list<HierarchyClassStructure>
	 */
	private static function flattenClasses(HierarchyClassStructure $structure): array
	{
		$groups = [];

		$parent = $structure->getParent();
		if ($parent !== null) {
			$groups[] = self::flattenClasses($parent);
		}

		foreach ($structure->getInterfaces() as $interface) {
			$groups[] = self::flattenClasses($interface);
		}

		foreach ($structure->getTraits() as $trait) {
			$groups[] = self::flattenClasses($trait);
		}

		$groups[][] = $structure;

		return array_merge(...$groups);
	}

	/**
	 * @param list<HierarchyClassStructure> $classes
	 * @return array<string, HierarchyClassStructure>
	 */
	private static function removeDuplicateClasses(array $classes): array
	{
		$deduplicated = [];
		foreach ($classes as $class) {
			$name = $class->getSource()->getReflector()->getName();

			if (isset($deduplicated[$name])) {
				continue;
			}

			$deduplicated[$name] = $class;
		}

		return $deduplicated;
	}

	/**
	 * @param array<string, HierarchyClassStructure> $classes
	 * @return list<ClassStructure>
	 */
	private static function unpackClasses(array $classes): array
	{
		$reduced = [];
		foreach ($classes as $class) {
			$reduced[] = new ClassStructure($class->getContextClass(), $class->getSource());
		}

		return $reduced;
	}

	/**
	 * @param array<string, HierarchyClassStructure> $classes
	 * @return list<ConstantStructure>
	 */
	private static function unpackConstants(array $classes): array
	{
		$groups = [];
		foreach ($classes as $class) {
			$groups[] = $class->getConstants();
		}

		return array_merge(...$groups);
	}

	/**
	 * @param array<string, HierarchyClassStructure> $classes
	 * @return list<PropertyStructure>
	 */
	private static function unpackProperties(array $classes): array
	{
		$groups = [];
		foreach ($classes as $class) {
			$groups[] = $class->getProperties();
		}

		return array_merge(...$groups);
	}

	/**
	 * @param list<PropertyStructure> $properties
	 * @return list<PropertyStructure>
	 */
	private static function removeDuplicateProperties(array $properties): array
	{
		$reduced = [];
		foreach ($properties as $property) {
			if ($property->getDuplicators() !== []) {
				continue;
			}

			$reduced[] = $property;
		}

		return $reduced;
	}

	/**
	 * @param array<string, HierarchyClassStructure> $classes
	 * @return list<MethodStructure>
	 */
	private static function unpackMethods(array $classes): array
	{
		$groups = [];
		foreach ($classes as $class) {
			$groups[] = $class->getMethods();
		}

		return array_merge(...$groups);
	}

}
