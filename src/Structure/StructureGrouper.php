<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

final class StructureGrouper
{

	public static function group(StructureList $list): StructureGroup
	{
		return new StructureGroup(
			$list->getClasses(),
			self::groupStructures($list->getConstants()),
			self::groupStructures($list->getProperties()),
			self::groupStructures($list->getMethods()),
		);
	}

	/**
	 * @template T of ConstantStructure|PropertyStructure|MethodStructure
	 * @param list<ConstantStructure|PropertyStructure|MethodStructure> $structures
	 * @phpstan-param list<T>                                           $structures
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
