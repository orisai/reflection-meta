<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\PropertySource;
use ReflectionClass;

final class PropertyStructure implements Structure
{

	/** @var ReflectionClass<object> */
	private ReflectionClass $contextClass;

	private PropertySource $source;

	/** @var array<ReflectionClass<object>> */
	private array $duplicators;

	/**
	 * @param ReflectionClass<object>        $contextClass
	 * @param array<ReflectionClass<object>> $duplicators
	 *
	 * @internal
	 */
	public function __construct(
		ReflectionClass $contextClass,
		PropertySource $source,
		array $duplicators
	)
	{
		$this->contextClass = $contextClass;
		$this->duplicators = $duplicators;
		$this->source = $source;
	}

	/**
	 * @return ReflectionClass<object>
	 */
	public function getContextClass(): ReflectionClass
	{
		return $this->contextClass;
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
