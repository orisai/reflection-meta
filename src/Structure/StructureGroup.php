<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

final class StructureGroup
{

	/** @var list<ClassStructure> */
	private array $classes;

	/** @var array<string, list<ConstantStructure>> */
	private array $constants;

	/** @var array<string, list<PropertyStructure>> */
	private array $properties;

	/** @var array<string, list<MethodStructure>> */
	private array $methods;

	/**
	 * @param list<ClassStructure>                   $classes
	 * @param array<string, list<ConstantStructure>> $constants
	 * @param array<string, list<PropertyStructure>> $properties
	 * @param array<string, list<MethodStructure>>   $methods
	 *
	 * @internal
	 * @see StructureGrouper::group()
	 */
	public function __construct(array $classes, array $constants, array $properties, array $methods)
	{
		$this->classes = $classes;
		$this->constants = $constants;
		$this->properties = $properties;
		$this->methods = $methods;
	}

	/**
	 * @return list<ClassStructure>
	 */
	public function getClasses(): array
	{
		return $this->classes;
	}

	/**
	 * @return array<string, list<ConstantStructure>>
	 */
	public function getGroupedConstants(): array
	{
		return $this->constants;
	}

	/**
	 * @return array<string, list<PropertyStructure>>
	 */
	public function getGroupedProperties(): array
	{
		return $this->properties;
	}

	/**
	 * @return array<string, list<MethodStructure>>
	 */
	public function getGroupedMethods(): array
	{
		return $this->methods;
	}

}
