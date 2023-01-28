<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined;

use Attribute;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"ALL"})
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
final class TestAttribute
{

}

/**
 * @TestAttribute()
 * @TestAttribute()
 */
#[TestAttribute]
#[TestAttribute]
final class ReaderDouble
{

	/**
	 * @TestAttribute()
	 * @TestAttribute()
	 */
	#[TestAttribute]
	#[TestAttribute]
	public const A = 'a';

	/**
	 * @TestAttribute()
	 * @TestAttribute()
	 */
	#[TestAttribute]
	#[TestAttribute]
	public string $a;

	/**
	 * @TestAttribute()
	 * @TestAttribute()
	 */
	#[TestAttribute]
	#[TestAttribute]
	public function a(
		/**
		 * @TestAttribute()
		 * @TestAttribute()
		 */
		#[TestAttribute]
		#[TestAttribute]
		string $a
	): void
	{
		// Meep.
	}

}
