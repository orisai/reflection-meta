<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\MethodSource;
use ReflectionClass;

final class MethodStructure implements Structure
{

	/** @var ReflectionClass<object> */
	private ReflectionClass $contextClass;

	private MethodSource $source;

	/** @var list<ParameterStructure> */
	private array $parameters;

	/**
	 * @param ReflectionClass<object>  $contextClass
	 * @param list<ParameterStructure> $parameters
	 */
	public function __construct(
		ReflectionClass $contextClass,
		MethodSource $source,
		array $parameters
	)
	{
		$this->contextClass = $contextClass;
		$this->source = $source;
		$this->parameters = $parameters;
	}

	/**
	 * @return ReflectionClass<object>
	 */
	public function getContextClass(): ReflectionClass
	{
		return $this->contextClass;
	}

	public function getSource(): MethodSource
	{
		return $this->source;
	}

	/**
	 * @return list<ParameterStructure>
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}

}
