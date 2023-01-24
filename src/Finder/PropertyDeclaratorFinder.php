<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Finder;

use Orisai\Exceptions\Logic\InvalidState;
use ReflectionClass;
use ReflectionProperty;
use function array_merge;
use function count;
use const PHP_VERSION_ID;

final class PropertyDeclaratorFinder
{

	/**
	 * @return array<ReflectionClass<object>>
	 */
	public static function getDeclaringTraits(ReflectionProperty $propertyReflector): array
	{
		$traits = self::getDeclaringTraitFromTraits(
			$propertyReflector->getDeclaringClass()->getTraits(),
			$propertyReflector,
		);
		self::checkTraitsCompatibility($propertyReflector, $traits);

		return $traits;
	}

	/**
	 * @param array<ReflectionClass<object>> $traits
	 * @return array<ReflectionClass<object>>
	 */
	private static function getDeclaringTraitFromTraits(
		array $traits,
		ReflectionProperty $propertyReflector
	): array
	{
		$possibleByTrait = [];
		foreach ($traits as $trait) {
			$possibleByTrait[] = $parentTraits = self::getDeclaringTraitFromTraits(
				$trait->getTraits(),
				$propertyReflector,
			);

			$name = $propertyReflector->getName();
			$hasProperty = $trait->hasProperty($name);

			// Parent traits don't have property, current trait is declaring one for current branch
			if ($hasProperty && $parentTraits === []) {
				$possibleByTrait[][] = $trait;
			}
		}

		return array_merge(...$possibleByTrait);
	}

	/**
	 * @param array<ReflectionClass<object>> $traits
	 */
	private static function checkTraitsCompatibility(ReflectionProperty $propertyReflector, array $traits): void
	{
		$name = $propertyReflector->getName();
		foreach ($traits as $traitA) {
			foreach ($traits as $traitB) {
				if (!self::arePropertiesCompatible(
					$traitA->getProperty($name),
					$traitB->getProperty($name),
				)) {
					throw InvalidState::create()
						->withMessage(
							"{$traitA->getName()} and {$traitB->getName()} define the same property"
							. " (\${$propertyReflector->getName()}) in the composition of"
							. " {$propertyReflector->getDeclaringClass()->getName()}."
							. ' However, the definition differs and is considered incompatible.',
						);
				}
			}
		}
	}

	/**
	 * DO NOT BLINDLY COPY-PASTE This method expects properties that are from parent and child properties from traits
	 * and will not work in other cases.
	 *
	 * If true is returned, property is probably declared by parent
	 * We don't really have a way how to check if exact same property is declared by both parent and
	 * child, but who cares about such case
	 *
	 * Other incompatibility checks are done by PHP (same modifiers, types, defaults)
	 */
	private static function arePropertiesCompatible(
		ReflectionProperty $parentProperty,
		ReflectionProperty $property
	): bool
	{
		if ($parentProperty->getDocComment() !== $property->getDocComment()) {
			return false;
		}

		if (PHP_VERSION_ID >= 8_00_00) {
			$parentAttributes = $parentProperty->getAttributes();
			$attributes = $property->getAttributes();

			if (count($parentAttributes) !== count($attributes)) {
				return false;
			}

			foreach ($parentAttributes as $key => $parentAttribute) {
				$attribute = $attributes[$key];

				if ($parentAttribute->getName() !== $attribute->getName()) {
					return false;
				}

				if ($parentAttribute->getArguments() !== $attribute->getArguments()) {
					return false;
				}
			}
		}

		return true;
	}

}
