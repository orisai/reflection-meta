<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\ClassSource;
use ReflectionClass;

final class HierarchyClassStructure
{

	private ?self $parent;

	/** @var list<HierarchyClassStructure> */
	private array $interfaces;

	/** @var list<HierarchyClassStructure> */
	private array $traits;

	/** @var list<ClassConstantStructure> */
	private array $constants;

	/** @var list<PropertyWithDuplicatesStructure> */
	private array $properties;

	/** @var list<MethodStructure> */
	private array $methods;

	private ClassSource $source;

	/** @var ReflectionClass<object> */
	private ReflectionClass $contextClass;

	/**
	 * @param ReflectionClass<object>               $contextClass
	 * @param list<HierarchyClassStructure>         $interfaces
	 * @param list<HierarchyClassStructure>         $traits
	 * @param list<ClassConstantStructure>          $constants
	 * @param list<PropertyWithDuplicatesStructure> $properties
	 * @param list<MethodStructure>                 $methods
	 *
	 * @internal
	 * @see StructureBuilder::build()
	 */
	public function __construct(
		ReflectionClass $contextClass,
		?self $parent,
		array $interfaces,
		array $traits,
		array $constants,
		array $properties,
		array $methods,
		ClassSource $source
	)
	{
		$this->contextClass = $contextClass;
		$this->parent = $parent;
		$this->interfaces = $interfaces;
		$this->traits = $traits;
		$this->constants = $constants;
		$this->properties = $properties;
		$this->methods = $methods;
		$this->source = $source;
	}

	/**
	 * @return ReflectionClass<object>
	 */
	public function getContextClass(): ReflectionClass
	{
		return $this->contextClass;
	}

	public function getParent(): ?self
	{
		return $this->parent;
	}

	/**
	 * @return list<HierarchyClassStructure>
	 */
	public function getInterfaces(): array
	{
		return $this->interfaces;
	}

	/**
	 * @return list<HierarchyClassStructure>
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
	 * @return list<PropertyWithDuplicatesStructure>
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
