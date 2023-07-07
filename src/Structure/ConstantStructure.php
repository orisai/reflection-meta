<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\ClassConstantSource;
use ReflectionClass;
use ReflectionClassConstant;

final class ConstantStructure implements Structure
{

	private ReflectionClassConstant $contextReflector;

	private ClassConstantSource $source;

	/** @var array<ReflectionClass<object>> */
	private array $duplicators;

	/**
	 * @param array<ReflectionClass<object>> $duplicators
	 */
	public function __construct(
		ReflectionClassConstant $contextReflector,
		ClassConstantSource $source,
		array $duplicators
	)
	{
		$this->contextReflector = $contextReflector;
		$this->source = $source;
		$this->duplicators = $duplicators;
	}

	public function getContextReflector(): ReflectionClassConstant
	{
		return $this->contextReflector;
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
