<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use function array_merge;
use function array_values;

final class StructureFlattener
{

	public function flatten(HierarchyClassStructure $classStructure): StructuresList
	{
		$classes = $this->removeDuplicateClasses($this->flattenClasses($classStructure));

		return new StructuresList(
			$this->unpackClasses($classes),
			$this->unpackConstants($classes),
			$this->removeDuplicateProperties($this->unpackProperties($classes)),
			$this->unpackMethods($classes),
		);
	}

	/**
	 * @return list<HierarchyClassStructure>
	 */
	private function flattenClasses(HierarchyClassStructure $structure): array
	{
		$groups = [];

		$parent = $structure->getParent();
		if ($parent !== null) {
			$groups[] = $this->flattenClasses($parent);
		}

		foreach ($structure->getInterfaces() as $interface) {
			$groups[] = $this->flattenClasses($interface);
		}

		foreach ($structure->getTraits() as $trait) {
			$groups[] = $this->flattenClasses($trait);
		}

		$groups[][] = $structure;

		return array_merge(...$groups);
	}

	/**
	 * @param list<HierarchyClassStructure> $classes
	 * @return list<HierarchyClassStructure>
	 */
	private function removeDuplicateClasses(array $classes): array
	{
		$deduplicated = [];
		foreach ($classes as $class) {
			$name = $class->getSource()->getReflector()->getName();

			if (isset($deduplicated[$name])) {
				continue;
			}

			$deduplicated[$name] = $class;
		}

		return array_values($deduplicated);
	}

	/**
	 * @param list<HierarchyClassStructure> $classes
	 * @return list<ClassStructure>
	 */
	private function unpackClasses(array $classes): array
	{
		$reduced = [];
		foreach ($classes as $class) {
			$reduced[] = new ClassStructure($class->getContextClass(), $class->getSource());
		}

		return $reduced;
	}

	/**
	 * @param list<HierarchyClassStructure> $classes
	 * @return list<ClassConstantStructure>
	 */
	private function unpackConstants(array $classes): array
	{
		$groups = [];
		foreach ($classes as $class) {
			$groups[] = $class->getConstants();
		}

		return array_merge(...$groups);
	}

	/**
	 * @param list<HierarchyClassStructure> $classes
	 * @return list<PropertyStructure>
	 */
	private function unpackProperties(array $classes): array
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
	private function removeDuplicateProperties(array $properties): array
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
	 * @param list<HierarchyClassStructure> $classes
	 * @return list<MethodStructure>
	 */
	private function unpackMethods(array $classes): array
	{
		$groups = [];
		foreach ($classes as $class) {
			$groups[] = $class->getMethods();
		}

		return array_merge(...$groups);
	}

}
