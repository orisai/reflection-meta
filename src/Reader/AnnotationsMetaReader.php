<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use Orisai\Utils\Dependencies\Dependencies;
use Orisai\Utils\Dependencies\Exception\PackageRequired;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

final class AnnotationsMetaReader implements MetaReader
{

	private Reader $reader;

	public function __construct(?Reader $reader = null)
	{
		if ($reader === null) {
			if (!self::canBeConstructed()) {
				throw PackageRequired::forClass(['doctrine/annotations'], self::class);
			}

			$reader = new AnnotationReader();
		}

		$this->reader = $reader;
	}

	public static function canBeConstructed(): bool
	{
		return Dependencies::getNotLoadedPackages(['doctrine/annotations']) === [];
	}

	public function readClass(ReflectionClass $class, string $definitionClass): array
	{
		return $this->filterInstances(
			$this->reader->getClassAnnotations($class),
			$definitionClass,
		);
	}

	public function readConstant(ReflectionClassConstant $constant, string $definitionClass): array
	{
		// Not supported
		return [];
	}

	public function readProperty(ReflectionProperty $property, string $definitionClass): array
	{
		return $this->filterInstances(
			$this->reader->getPropertyAnnotations($property),
			$definitionClass,
		);
	}

	public function readMethod(ReflectionMethod $method, string $definitionClass): array
	{
		return $this->filterInstances(
			$this->reader->getMethodAnnotations($method),
			$definitionClass,
		);
	}

	public function readParameter(ReflectionParameter $parameter, string $definitionClass): array
	{
		// Not supported
		return [];
	}

	/**
	 * @template T of object
	 * @param array<object>   $attributes
	 * @param class-string<T> $definitionClass
	 * @return list<T>
	 */
	private function filterInstances(array $attributes, string $definitionClass): array
	{
		$instances = [];
		foreach ($attributes as $attribute) {
			if (!$attribute instanceof $definitionClass) {
				continue;
			}

			$instances[] = $attribute;
		}

		return $instances;
	}

}
