<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

final class StructureGrouper
{

	public static function group(StructuresList $list): StructuresGroup
	{
		return new StructuresGroup(
			$list->getClasses(),
			self::groupStructures($list->getConstants()),
			self::groupStructures($list->getProperties()),
			self::groupStructures($list->getMethods()),
		);
	}

	/**
	 * @template T of ClassConstantStructure|PropertyStructure|MethodStructure
	 * @param list<ClassConstantStructure|PropertyStructure|MethodStructure> $structures
	 * @phpstan-param list<T>                                                $structures
	 * @return array<string, list<T>>
	 */
	private static function groupStructures(array $structures): array
	{
		$grouped = [];
		foreach ($structures as $structure) {
			$reflector = $structure->getSource()->getReflector();
			$id = $reflector->isPrivate()
				? "{$reflector->getDeclaringClass()->getName()}::{$reflector->getName()}"
				: "::{$reflector->getName()}";

			$grouped[$id][] = $structure;
		}

		return $grouped;
	}

}
