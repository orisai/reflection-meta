<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\ParameterSource;
use ReflectionParameter;

final class ParameterStructure implements Structure
{

	private ParameterSource $source;

	private ReflectionParameter $contextReflector;

	public function __construct(
		ReflectionParameter $contextReflector,
		ParameterSource $source
	)
	{
		$this->source = $source;
		$this->contextReflector = $contextReflector;
	}

	public function getContextReflector(): ReflectionParameter
	{
		return $this->contextReflector;
	}

	public function getSource(): ParameterSource
	{
		return $this->source;
	}

}
