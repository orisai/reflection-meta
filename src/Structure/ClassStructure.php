<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\ClassSource;

final class ClassStructure implements Structure
{

	private ClassSource $source;

	public function __construct(ClassSource $source)
	{
		$this->source = $source;
	}

	public function getSource(): ClassSource
	{
		return $this->source;
	}

}
