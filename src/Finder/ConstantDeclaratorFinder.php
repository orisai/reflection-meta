<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Finder;

use Orisai\Exceptions\Logic\InvalidState;
use ReflectionClass;
use ReflectionClassConstant;
use function array_merge;
use function count;
use const PHP_VERSION_ID;

final class ConstantDeclaratorFinder
{

	/**
	 * @return array<ReflectionClass<object>>
	 */
	public static function getDeclaringTraits(ReflectionClassConstant $constantReflector): array
	{
		$traits = self::getDeclaringTraitFromTraits(
			$constantReflector->getDeclaringClass()->getTraits(),
			$constantReflector,
		);
		self::checkTraitsCompatibility($constantReflector, $traits);

		return $traits;
	}

	/**
	 * @param array<ReflectionClass<object>> $traits
	 * @return array<ReflectionClass<object>>
	 */
	private static function getDeclaringTraitFromTraits(
		array $traits,
		ReflectionClassConstant $constantReflector
	): array
	{
		$possibleByTrait = [];
		foreach ($traits as $trait) {
			$possibleByTrait[] = $parentTraits = self::getDeclaringTraitFromTraits(
				$trait->getTraits(),
				$constantReflector,
			);

			$name = $constantReflector->getName();
			$hasProperty = $trait->hasProperty($name);

			// Parent traits don't have constant, current trait is declaring one for current branch
			if ($hasProperty && $parentTraits === []) {
				$possibleByTrait[][] = $trait;
			}
		}

		return array_merge(...$possibleByTrait);
	}

	/**
	 * @param array<ReflectionClass<object>> $traits
	 */
	private static function checkTraitsCompatibility(ReflectionClassConstant $constantReflector, array $traits): void
	{
		$name = $constantReflector->getName();
		foreach ($traits as $traitA) {
			foreach ($traits as $traitB) {
				if (!self::areConstantsCompatible(
					$traitA->getConstant($name),
					$traitB->getConstant($name),
				)) {
					throw InvalidState::create()
						->withMessage(
							"{$traitA->getName()} and {$traitB->getName()} define the same constant"
							. " (\${$constantReflector->getName()}) in the composition of"
							. " {$constantReflector->getDeclaringClass()->getName()}."
							. ' However, the definition differs and is considered incompatible.',
						);
				}
			}
		}
	}

	/**
	 * DO NOT BLINDLY COPY-PASTE This method expects constants that are from parent and child constants from traits
	 * and will not work in other cases.
	 *
	 * If true is returned, constant is probably declared by parent
	 * We don't really have a way how to check if exact same property is declared by both parent and
	 * child, but who cares about such case
	 *
	 * Other incompatibility checks are done by PHP (same modifiers, types)
	 */
	private static function areConstantsCompatible(
		ReflectionClassConstant $parentConstantReflector,
		ReflectionClassConstant $constantReflector
	): bool
	{
		if ($parentConstantReflector->getDocComment() !== $constantReflector->getDocComment()) {
			return false;
		}

		if (PHP_VERSION_ID >= 8_00_00) {
			$parentAttributes = $parentConstantReflector->getAttributes();
			$attributes = $constantReflector->getAttributes();

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
