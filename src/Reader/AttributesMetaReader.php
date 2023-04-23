<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Reader;

use Orisai\Exceptions\Logic\InvalidState;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use function assert;
use function is_a;
use const PHP_VERSION_ID;

final class AttributesMetaReader implements MetaReader
{

	public function __construct()
	{
		if (!self::canBeConstructed()) {
			/** @infection-ignore-all */
			throw InvalidState::create()
				->withMessage('Attributes are supported since PHP 8.0');
		}
	}

	public static function canBeConstructed(): bool
	{
		/** @infection-ignore-all */
		return PHP_VERSION_ID >= 8_00_00;
	}

	public function readClass(ReflectionClass $class, string $attributeClass): array
	{
		return $this->attributesToInstances($class->getAttributes(), $attributeClass);
	}

	public function readConstant(ReflectionClassConstant $constant, string $attributeClass): array
	{
		return $this->attributesToInstances($constant->getAttributes(), $attributeClass);
	}

	public function readProperty(ReflectionProperty $property, string $attributeClass): array
	{
		return $this->attributesToInstances($property->getAttributes(), $attributeClass);
	}

	public function readMethod(ReflectionMethod $method, string $attributeClass): array
	{
		return $this->attributesToInstances($method->getAttributes(), $attributeClass);
	}

	public function readParameter(ReflectionParameter $parameter, string $attributeClass): array
	{
		return $this->attributesToInstances($parameter->getAttributes(), $attributeClass);
	}

	/**
	 * @template T of object
	 * @param array<ReflectionAttribute<object>> $attributes
	 * @param class-string<T>                    $attributeClass
	 * @return list<T>
	 */
	private function attributesToInstances(array $attributes, string $attributeClass): array
	{
		$instances = [];
		foreach ($attributes as $attribute) {
			if (!is_a($attribute->getName(), $attributeClass, true)) {
				continue;
			}

			$instance = $attribute->newInstance();
			assert($instance instanceof $attributeClass);
			$instances[] = $instance;
		}

		return $instances;
	}

}
