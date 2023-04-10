<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Finder;

use ReflectionClass;
use ReflectionProperty;
use function array_merge;
use function count;
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

		if (PHP_VERSION_ID >= 8_00_00) {
			$attributes1 = $property1->getAttributes();
			$attributes2 = $property2->getAttributes();

			if (count($attributes1) !== count($attributes2)) {
				return false;
			}

			foreach ($attributes1 as $key => $attribute1) {
				$attribute2 = $attributes2[$key];

				if ($attribute1->getName() !== $attribute2->getName()) {
					return false;
				}

				if ($attribute1->getArguments() !== $attribute2->getArguments()) {
					return false;
				}
			}
		}

		return true;
	}

}
