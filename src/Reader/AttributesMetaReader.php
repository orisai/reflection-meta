<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Reader;

use Orisai\Exceptions\Logic\InvalidState;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use const PHP_VERSION_ID;

final class AttributesMetaReader implements MetaReader
{

	public function __construct()
	{
		if (!self::canBeConstructed()) {
			throw InvalidState::create()
				->withMessage('Attributes are supported since PHP 8.0');
		}
	}

	public static function canBeConstructed(): bool
	{
		return PHP_VERSION_ID >= 8_00_00;
	}

	public function readClass(ReflectionClass $class): array
	{
		return $this->attributesToInstances($class->getAttributes());
	}

	public function readClassConstant(ReflectionClassConstant $constant): array
	{
		return $this->attributesToInstances($constant->getAttributes());
	}

	public function readProperty(ReflectionProperty $property): array
	{
		return $this->attributesToInstances($property->getAttributes());
	}

	public function readMethod(ReflectionMethod $method): array
	{
		return $this->attributesToInstances($method->getAttributes());
	}

	public function readParameter(ReflectionParameter $parameter): array
	{
		return $this->attributesToInstances($parameter->getAttributes());
	}

	/**
	 * @param array<ReflectionAttribute<object>> $attributes
	 * @return list<object>
	 */
	private function attributesToInstances(array $attributes): array
	{
		$instances = [];
		foreach ($attributes as $attribute) {
			$instances[] = $attribute->newInstance();
		}

		return $instances;
	}

}
