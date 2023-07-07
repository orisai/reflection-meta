<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\ParameterSource;

final class ParameterStructure implements Structure
{

	private ParameterSource $source;

	public function __construct(ParameterSource $source)
	{
		$this->source = $source;
	}

	public function getSource(): ParameterSource
	{
		return $this->source;
	}

}
