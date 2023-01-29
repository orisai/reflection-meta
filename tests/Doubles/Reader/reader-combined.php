<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined;

use Attribute;
use Doctrine\Common\Annotations\Annotation\Target;

abstract class BaseTestAttribute
{

}

/**
 * @Annotation
 * @Target({"ALL"})
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class TestAttribute1 extends BaseTestAttribute
{

}

/**
 * @Annotation
 * @Target({"ALL"})
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class TestAttribute2 extends BaseTestAttribute
{

}

final class UnusedAttribute
{

}

/**
 * @TestAttribute1()
 * @TestAttribute2()
 */
#[TestAttribute1]
#[TestAttribute2]
final class ReaderDouble
{

	/**
	 * @TestAttribute1()
	 * @TestAttribute2()
	 */
	#[TestAttribute1]
	#[TestAttribute2]
	public const A = 'a';

	/**
	 * @TestAttribute1()
	 * @TestAttribute2()
	 */
	#[TestAttribute1]
	#[TestAttribute2]
	public string $a;

	/**
	 * @TestAttribute1()
	 * @TestAttribute2()
	 */
	#[TestAttribute1]
	#[TestAttribute2]
	public function a(
		/**
		 * @TestAttribute1()
		 * @TestAttribute2()
		 */
		#[TestAttribute1]
		#[TestAttribute2]
		string $a
	): void
	{
		// Meep.
	}

}
