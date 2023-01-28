<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\ClassConstantSource;

final class ClassConstantStructure implements Structure
{

	private ClassConstantSource $source;

	public function __construct(ClassConstantSource $source)
	{
		$this->source = $source;
	}

	public function getSource(): ClassConstantSource
	{
		return $this->source;
	}

}
