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

	public function readClass(ReflectionClass $class, string $attributeClass): array
	{
		return $this->filterInstances(
			$this->reader->getClassAnnotations($class),
			$attributeClass,
		);
	}

	public function readConstant(ReflectionClassConstant $constant, string $attributeClass): array
	{
		// Not supported
		return [];
	}

	public function readProperty(ReflectionProperty $property, string $attributeClass): array
	{
		return $this->filterInstances(
			$this->reader->getPropertyAnnotations($property),
			$attributeClass,
		);
	}

	public function readMethod(ReflectionMethod $method, string $attributeClass): array
	{
		return $this->filterInstances(
			$this->reader->getMethodAnnotations($method),
			$attributeClass,
		);
	}

	public function readParameter(ReflectionParameter $parameter, string $attributeClass): array
	{
		// Not supported
		return [];
	}

	/**
	 * @template T of object
	 * @param array<object>   $attributes
	 * @param class-string<T> $attributeClass
	 * @return list<T>
	 */
	private function filterInstances(array $attributes, string $attributeClass): array
	{
		$instances = [];
		foreach ($attributes as $attribute) {
			if (!$attribute instanceof $attributeClass) {
				continue;
			}

			$instances[] = $attribute;
		}

		return $instances;
	}

}
