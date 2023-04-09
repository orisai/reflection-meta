<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\PropertySource;
use ReflectionClass;

final class PropertyStructure implements Structure
{

	/** @var ReflectionClass<object> */
	private ReflectionClass $contextClass;

	private PropertySource $source;

	/**
	 * @param ReflectionClass<object> $contextClass
	 *
	 * @internal
	 */
	public function __construct(
		ReflectionClass $contextClass,
		PropertySource $source
	)
	{
		$this->contextClass = $contextClass;
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

}
