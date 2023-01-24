<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Finder;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use ReflectionClass;
use ReflectionMethod;
use function array_map;
use function array_merge;
use function array_pop;
use function count;
use function implode;

final class MethodDeclaratorFinder
{

	/**
	 * @return ReflectionClass<object>|null
	 */
	public static function getDeclaringTrait(ReflectionMethod $methodReflector): ?ReflectionClass
	{
		$classReflector = $methodReflector->getDeclaringClass();

		$possible = self::getPossibleDeclaringTraits($classReflector->getTraits(), $methodReflector);

		if (count($possible) > 1) {
			$methodName = $methodReflector->getDeclaringClass()->getName()
				. ($methodReflector->isStatic() ? '::' : '->')
				. $methodReflector->getName();

			$possibleInline = implode(
				"', '",
				array_map(
					static fn (ReflectionClass $class): string => $class->getName(),
					$possible,
				),
			);

			$message = Message::create()
				->withContext("Checking which trait declared method '$methodName()'")
				->withProblem("These traits are on the same line: '$possibleInline'")
				->withSolution('Don\'t place them on the same line.');

			throw InvalidState::create()
				->withMessage($message);
		}

		if (count($possible) === 1) {
			return array_pop($possible);
		}

		return null;
	}

	/**
	 * @param array<ReflectionClass<object>> $traits
	 * @return array<ReflectionClass<object>>
	 */
	private static function getPossibleDeclaringTraits(array $traits, ReflectionMethod $methodReflector): array
	{
		$possibleByTrait = [];
		foreach ($traits as $trait) {
			// Neither current nor inner trait has method
			if (!$trait->hasMethod($methodReflector->getName())) {
				continue;
			}

			// It may be the trait declaring method, method is on matching line of the same file
			if (
				$trait->getFileName() === $methodReflector->getFileName()
				&& $trait->getStartLine() <= $methodReflector->getStartLine()
				&& $trait->getEndLine() >= $methodReflector->getEndLine()
			) {
				$possibleByTrait[][] = $trait;
			}

			$possibleByTrait[] = self::getPossibleDeclaringTraits(
				$trait->getTraits(),
				$methodReflector,
			);
		}

		return array_merge(...$possibleByTrait);
	}

}
