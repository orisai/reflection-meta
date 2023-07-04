<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Filter;

use Generator;
use Orisai\ReflectionMeta\Filter\AnnotationFilter;
use PHPUnit\Framework\TestCase;

final class AnnotationFilterTest extends TestCase
{

	/**
	 * @dataProvider provide
	 */
	public function test(string $given, string $expected): void
	{
		self::assertSame($expected, AnnotationFilter::filterMultilineDocblock($given));
	}

	public function provide(): Generator
	{
		yield [
			'single line docblock',
			'single line docblock',
		];

		yield [
			'* single line docblock',
			'* single line docblock',
		];

		yield [
			<<<'MSG'
* Multi
*  line
*   docblock
MSG,
			<<<'MSG'
Multi
 line
  docblock
MSG,
		];

		yield [
			<<<'MSG'
*Multi
*  line
*   docblock
MSG,
			<<<'MSG'
Multi
 line
  docblock
MSG,
		];

		yield [
			<<<'MSG'
* Multi
** line
** docblock
MSG,
			<<<'MSG'
Multi
* line
* docblock
MSG,
		];

		yield [
			<<<'MSG'
*      Multi
*       line
*        docblock
MSG,
			<<<'MSG'
     Multi
      line
       docblock
MSG,
		];

		yield [
			<<<'MSG'
*		Multi
*		 line
*         docblock
MSG,
			<<<'MSG'
		Multi
		 line
        docblock
MSG,
		];

		yield [
			<<<'MSG'

* Multi

*  line
*   docblock

MSG,
			<<<'MSG'
Multi

 line
  docblock
MSG,
		];

		yield [
			<<<'MSG'


* Multi

*  line
*   docblock


MSG,
			<<<'MSG'

Multi

 line
  docblock

MSG,
		];
	}

}
