<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\PropertySource;
use ReflectionClass;
use ReflectionProperty;

final class PropertyStructure implements Structure
{

	private ReflectionProperty $contextReflector;

	private PropertySource $source;

	/** @var array<ReflectionClass<object>> */
	private array $duplicators;

	/**
	 * @param array<ReflectionClass<object>> $duplicators
	 */
	public function __construct(
		ReflectionProperty $contextReflector,
		PropertySource $source,
		array $duplicators
	)
	{
		$this->contextReflector = $contextReflector;
		$this->duplicators = $duplicators;
		$this->source = $source;
	}

	public function getContextReflector(): ReflectionProperty
	{
		return $this->contextReflector;
	}

	public function getSource(): PropertySource
	{
		return $this->source;
	}

	/**
	 * @return array<ReflectionClass<object>>
	 */
	public function getDuplicators(): array
	{
		return $this->duplicators;
	}

}
