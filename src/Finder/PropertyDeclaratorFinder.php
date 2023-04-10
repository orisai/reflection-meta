<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Finder;

use ReflectionClass;
use ReflectionProperty;
use function array_merge;
use const PHP_VERSION_ID;

final class PropertyDeclaratorFinder
{

	/**
	 * @return list<ReflectionClass<object>>
	 */
	public static function getDeclaringTraits(ReflectionProperty $propertyReflector): array
	{
		return self::getDeclaringTraitFromTraits(
			$propertyReflector->getDeclaringClass()->getTraits(),
			$propertyReflector,
		);
	}

	/**
	 * @param array<ReflectionClass<object>> $traits
	 * @return list<ReflectionClass<object>>
	 */
	private static function getDeclaringTraitFromTraits(
		array $traits,
		ReflectionProperty $propertyReflector
	): array
	{
		$possibleByTrait = [];
		foreach ($traits as $trait) {
			$possibleByTrait[] = $usedTraits = self::getDeclaringTraitFromTraits(
				$trait->getTraits(),
				$propertyReflector,
			);

			$name = $propertyReflector->getName();

			if (!$trait->hasProperty($name)) {
				continue;
			}

			foreach ($usedTraits as $usedTrait) {
				if (self::areDefinitionsIdentical($propertyReflector, $usedTrait->getProperty($name))) {
					continue 2;
				}
			}

			$possibleByTrait[][] = $trait;
		}

		return array_merge(...$possibleByTrait);
	}

	private static function areDefinitionsIdentical(
		ReflectionProperty $property1,
		ReflectionProperty $property2
	): bool
	{
		if ($property1->getDocComment() !== $property2->getDocComment()) {
			return false;
		}

		// Intentionally loose !=
		// phpcs:ignore SlevomatCodingStandard.ControlStructures.UselessIfConditionWithReturn
		if (PHP_VERSION_ID >= 8_00_00 && $property1->getAttributes() != $property2->getAttributes()) {
			return false;
		}

		return true;
	}

}
