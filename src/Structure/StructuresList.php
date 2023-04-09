<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

final class StructuresList
{

	/** @var list<ClassStructure> */
	private array $classes;

	/** @var list<ClassConstantStructure> */
	private array $constants;

	/** @var list<PropertyStructure> */
	private array $properties;

	/** @var list<MethodStructure> */
	private array $methods;

	/**
	 * @param list<ClassStructure>         $classes
	 * @param list<ClassConstantStructure> $constants
	 * @param list<PropertyStructure>      $properties
	 * @param list<MethodStructure>        $methods
	 *
	 * @internal
	 * @see StructureFlattener::flatten()
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
	 * @return list<ClassConstantStructure>
	 */
	public function getConstants(): array
	{
		return $this->constants;
	}

	/**
	 * @return list<PropertyStructure>
	 */
	public function getProperties(): array
	{
		return $this->properties;
	}

	/**
	 * @return list<MethodStructure>
	 */
	public function getMethods(): array
	{
		return $this->methods;
	}

}
