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

	public function readClass(ReflectionClass $class): array
	{
		return $this->reader->getClassAnnotations($class);
	}

	public function readClassConstant(ReflectionClassConstant $constant): array
	{
		// Not supported
		return [];
	}

	public function readProperty(ReflectionProperty $property): array
	{
		return $this->reader->getPropertyAnnotations($property);
	}

	public function readMethod(ReflectionMethod $method): array
	{
		return $this->reader->getMethodAnnotations($method);
	}

	public function readParameter(ReflectionParameter $parameter): array
	{
		// Not supported
		return [];
	}

}
