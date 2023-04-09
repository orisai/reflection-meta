<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\ClassConstantSource;
use ReflectionClass;

final class ClassConstantStructure implements Structure
{

	/** @var ReflectionClass<object> */
	private ReflectionClass $contextClass;

	private ClassConstantSource $source;

	/** @var array<ReflectionClass<object>> */
	private array $duplicators;

	/**
	 * @param ReflectionClass<object>        $contextClass
	 * @param array<ReflectionClass<object>> $duplicators
	 */
	public function __construct(
		ReflectionClass $contextClass,
		ClassConstantSource $source,
		array $duplicators
	)
	{
		$this->contextClass = $contextClass;
		$this->source = $source;
		$this->duplicators = $duplicators;
	}

	/**
	 * @return ReflectionClass<object>
	 */
	public function getContextClass(): ReflectionClass
	{
		return $this->contextClass;
	}

	public function getSource(): ClassConstantSource
	{
		return $this->source;
	}

	/**
	 * @return array<ReflectionClass<object>>
	 */
	public function getDuplicators(): array
	{
		return $this->duplicators;
	}

}
