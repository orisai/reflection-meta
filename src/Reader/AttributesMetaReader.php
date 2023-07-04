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

	public function readClass(ReflectionClass $class, string $definitionClass): array
	{
		return $this->attributesToInstances($class->getAttributes(), $definitionClass);
	}

	public function readConstant(ReflectionClassConstant $constant, string $definitionClass): array
	{
		return $this->attributesToInstances($constant->getAttributes(), $definitionClass);
	}

	public function readProperty(ReflectionProperty $property, string $definitionClass): array
	{
		return $this->attributesToInstances($property->getAttributes(), $definitionClass);
	}

	public function readMethod(ReflectionMethod $method, string $definitionClass): array
	{
		return $this->attributesToInstances($method->getAttributes(), $definitionClass);
	}

	public function readParameter(ReflectionParameter $parameter, string $definitionClass): array
	{
		return $this->attributesToInstances($parameter->getAttributes(), $definitionClass);
	}

	/**
	 * @template T of object
	 * @param array<ReflectionAttribute<object>> $attributes
	 * @param class-string<T>                    $definitionClass
	 * @return list<T>
	 */
	private function attributesToInstances(array $attributes, string $definitionClass): array
	{
		$instances = [];
		foreach ($attributes as $attribute) {
			if (!is_a($attribute->getName(), $definitionClass, true)) {
				continue;
			}

			$instance = $attribute->newInstance();
			assert($instance instanceof $definitionClass);
			$instances[] = $instance;
		}

		return $instances;
	}

}
