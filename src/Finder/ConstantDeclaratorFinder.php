<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Finder;

use ReflectionClass;
use ReflectionClassConstant;
use function array_merge;
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

		// Intentionally loose !=
		// phpcs:ignore SlevomatCodingStandard.ControlStructures.UselessIfConditionWithReturn
		if (PHP_VERSION_ID >= 8_00_00 && $constant1->getAttributes() != $constant2->getAttributes()) {
			return false;
		}

		return true;
	}

}
