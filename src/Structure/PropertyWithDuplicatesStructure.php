<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\PropertySource;
use ReflectionClass;

final class PropertyWithDuplicatesStructure implements Structure
{

	/** @var ReflectionClass<object> */
	private ReflectionClass $contextClass;

	/** @var array<ReflectionClass<object>> */
	private array $duplicateDeclarations;

	private PropertySource $source;

	/**
	 * @param ReflectionClass<object> $contextClass
	 * @param array<ReflectionClass<object>> $duplicateDeclarations
	 *
	 * @internal
	 */
	public function __construct(
		ReflectionClass $contextClass,
		array $duplicateDeclarations,
		PropertySource $source
	)
	{
		$this->contextClass = $contextClass;
		$this->duplicateDeclarations = $duplicateDeclarations;
		$this->source = $source;
	}

	/**
	 * @return ReflectionClass<object>
	 */
	public function getContextClass(): ReflectionClass
	{
		return $this->contextClass;
	}

	/**
	 * @return array<ReflectionClass<object>>
	 */
	public function getDuplicateDeclarations(): array
	{
		return $this->duplicateDeclarations;
	}

	public function getSource(): PropertySource
	{
		return $this->source;
	}

}
