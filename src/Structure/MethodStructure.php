<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\MethodSource;
use ReflectionMethod;

final class MethodStructure implements Structure
{

	private ReflectionMethod $contextReflector;

	private MethodSource $source;

	/** @var list<ParameterStructure> */
	private array $parameters;

	/**
	 * @param list<ParameterStructure> $parameters
	 */
	public function __construct(
		ReflectionMethod $contextReflector,
		MethodSource $source,
		array $parameters
	)
	{
		$this->contextReflector = $contextReflector;
		$this->source = $source;
		$this->parameters = $parameters;
	}

	public function getContextReflector(): ReflectionMethod
	{
		return $this->contextReflector;
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
