<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\ClassSource;

final class ClassStructure
{

	private ?ClassStructure $parent;

	/** @var list<ClassStructure> */
	private array $interfaces;

	/** @var list<ClassStructure> */
	private array $traits;

	/** @var list<ClassConstantStructure> */
	private array $constants;

	/** @var list<PropertyStructure> */
	private array $properties;

	/** @var list<MethodStructure> */
	private array $methods;

	private ClassSource $source;

	/**
	 * @param list<ClassStructure>         $interfaces
	 * @param list<ClassStructure>         $traits
	 * @param list<ClassConstantStructure> $constants
	 * @param list<PropertyStructure>      $properties
	 * @param list<MethodStructure>        $methods
	 */
	public function __construct(
		?ClassStructure $parent,
		array $interfaces,
		array $traits,
		array $constants,
		array $properties,
		array $methods,
		ClassSource $source
	)
	{
		$this->parent = $parent;
		$this->interfaces = $interfaces;
		$this->traits = $traits;
		$this->constants = $constants;
		$this->properties = $properties;
		$this->methods = $methods;
		$this->source = $source;
	}

	public function getParent(): ?ClassStructure
	{
		return $this->parent;
	}

	/**
	 * @return list<ClassStructure>
	 */
	public function getInterfaces(): array
	{
		return $this->interfaces;
	}

	/**
	 * @return list<ClassStructure>
	 */
	public function getTraits(): array
	{
		return $this->traits;
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

	public function getSource(): ClassSource
	{
		return $this->source;
	}

}
