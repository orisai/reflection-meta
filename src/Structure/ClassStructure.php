<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\ClassSource;
use ReflectionClass;

final class ClassStructure implements Structure
{

	/** @var ReflectionClass<object> */
	private ReflectionClass $contextReflector;

	private ClassSource $source;

	/**
	 * @param ReflectionClass<object> $contextReflector
	 */
	public function __construct(ReflectionClass $contextReflector, ClassSource $source)
	{
		$this->contextReflector = $contextReflector;
		$this->source = $source;
	}

	/**
	 * @return ReflectionClass<object>
	 */
	public function getContextReflector(): ReflectionClass
	{
		return $this->contextReflector;
	}

	public function getSource(): ClassSource
	{
		return $this->source;
	}

}
