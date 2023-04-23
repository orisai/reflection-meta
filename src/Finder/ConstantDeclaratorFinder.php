<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Finder;

use ReflectionClass;
use ReflectionClassConstant;
use function array_merge;
use function count;
use const PHP_VERSION_ID;

final class ConstantDeclaratorFinder
{

	/**
	 * @return list<ReflectionClass<object>>
	 */
	public static function getDeclaringTraits(ReflectionClassConstant $constantReflector): array
	{
		return self::getDeclaringTraitFromTraits(
			$constantReflector->getDeclaringClass(),
			$constantReflector,
		);
	}

	/**
	 * @param ReflectionClass<object> $declaringClass
	 * @return list<ReflectionClass<object>>
	 */
	private static function getDeclaringTraitFromTraits(
		ReflectionClass $declaringClass,
		ReflectionClassConstant $constantReflector
	): array
	{
		$possibleByTrait = [];
		foreach ($declaringClass->getTraits() as $trait) {
			$name = $constantReflector->getName();
			if (!$trait->hasConstant($name)) {
				continue;
			}

			$possibleByTrait[] = $usedTraits = self::getDeclaringTraitFromTraits(
				$trait,
				$constantReflector,
			);

			foreach ($usedTraits as $usedTrait) {
				if (self::areDefinitionsIdentical($constantReflector, $usedTrait->getReflectionConstant($name))) {
					continue 2;
				}
			}

			$possibleByTrait[][] = $trait;
		}

		return array_merge(...$possibleByTrait);
	}

	private static function areDefinitionsIdentical(
		ReflectionClassConstant $constant1,
		ReflectionClassConstant $constant2
	): bool
	{
		if ($constant1->getDocComment() !== $constant2->getDocComment()) {
			return false;
		}

		/** @infection-ignore-all */
		if (PHP_VERSION_ID >= 8_00_00) {
			$attributes1 = $constant1->getAttributes();
			$attributes2 = $constant2->getAttributes();

			if (count($attributes1) !== count($attributes2)) {
				return false;
			}

			foreach ($attributes1 as $key => $attribute1) {
				$attribute2 = $attributes2[$key];

				if ($attribute1->getName() !== $attribute2->getName()) {
					return false;
				}

				// Intentionally loose !=
				if ($attribute1->getArguments() != $attribute2->getArguments()) {
					return false;
				}
			}
		}

		return true;
	}

}
