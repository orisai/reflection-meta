<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\MethodSource;
use ReflectionClass;

final class MethodStructure implements Structure
{

	/** @var ReflectionClass<object> */
	private ReflectionClass $contextClass;

	/** @var list<ParameterStructure> */
	private array $parameters;

	private MethodSource $source;

	/**
	 * @param ReflectionClass<object>      $contextClass
	 * @param list<ParameterStructure>     $parameters
	 *
	 * @internal
	 */
	public function __construct(
		ReflectionClass $contextClass,
		array $parameters,
		MethodSource $source
	)
	{
		$this->contextClass = $contextClass;
		$this->parameters = $parameters;
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
	 * @return list<ParameterStructure>
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}

	public function getSource(): MethodSource
	{
		return $this->source;
	}

}
