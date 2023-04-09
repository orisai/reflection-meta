<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\ClassSource;
use ReflectionClass;

final class ClassStructure implements Structure
{

	/** @var ReflectionClass<object> */
	private ReflectionClass $contextClass;

	private ClassSource $source;

	/**
	 * @param ReflectionClass<object> $contextClass
	 *
	 * @internal
	 */
	public function __construct(ReflectionClass $contextClass, ClassSource $source)
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

	public function getSource(): ClassSource
	{
		return $this->source;
	}

}
