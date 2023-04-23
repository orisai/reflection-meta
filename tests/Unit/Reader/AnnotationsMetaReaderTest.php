<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use Orisai\ReflectionMeta\Reader\AnnotationsMetaReader;
use Orisai\Utils\Dependencies\DependenciesTester;
use Orisai\Utils\Dependencies\Exception\PackageRequired;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\BaseTestAttribute;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\ReaderDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\TestAttribute1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\TestAttribute2;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\UnusedAttribute;

final class AnnotationsMetaReaderTest extends TestCase
{

	public function test(): void
	{
		require_once __DIR__ . '/../../Doubles/Reader/reader-combined.php';

		$reader = new AnnotationsMetaReader();
		$class = new ReflectionClass(ReaderDouble::class);
		$expected = [
			new TestAttribute1(),
			new TestAttribute2(),
		];
		$expected2 = [
			new TestAttribute2(),
		];

		self::assertEquals(
			$reader->readClass($class, BaseTestAttribute::class),
			$expected,
		);
		self::assertEquals(
			$reader->readClass($class, TestAttribute2::class),
			$expected2,
		);
		self::assertEquals(
			$reader->readClass($class, UnusedAttribute::class),
			[],
		);

		$constant = $class->getReflectionConstant('A');
		self::assertEquals(
			$reader->readConstant($constant, BaseTestAttribute::class),
			[],
		);
		self::assertEquals(
			$reader->readConstant($constant, TestAttribute2::class),
			[],
		);
		self::assertEquals(
			$reader->readConstant($constant, UnusedAttribute::class),
			[],
		);

		$property = $class->getProperty('a');
		self::assertEquals(
			$reader->readProperty($property, BaseTestAttribute::class),
			$expected,
		);
		self::assertEquals(
			$reader->readProperty($property, TestAttribute2::class),
			$expected2,
		);
		self::assertEquals(
			$reader->readProperty($property, UnusedAttribute::class),
			[],
		);

		$method = $class->getMethod('a');
		self::assertEquals(
			$reader->readMethod($method, BaseTestAttribute::class),
			$expected,
		);
		self::assertEquals(
			$reader->readMethod($method, TestAttribute2::class),
			$expected2,
		);
		self::assertEquals(
			$reader->readMethod($method, UnusedAttribute::class),
			[],
		);

		$parameter = $method->getParameters()[0];
		self::assertEquals(
			$reader->readParameter($parameter, BaseTestAttribute::class),
			[],
		);
		self::assertEquals(
			$reader->readParameter($parameter, TestAttribute2::class),
			[],
		);
		self::assertEquals(
			$reader->readParameter($parameter, UnusedAttribute::class),
			[],
		);
	}

	public function testDependency(): void
	{
		require_once __DIR__ . '/../../Doubles/Reader/reader-combined.php';

		$reader = new AnnotationsMetaReader(new AnnotationReader());
		$class = new ReflectionClass(ReaderDouble::class);
		$expected = [
			new TestAttribute1(),
			new TestAttribute2(),
		];

		self::assertEquals(
			$reader->readClass($class, BaseTestAttribute::class),
			$expected,
		);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testMissingDependency(): void
	{
		self::assertTrue(AnnotationsMetaReader::canBeConstructed());

		DependenciesTester::addIgnoredPackages(['doctrine/annotations']);
		self::assertFalse(AnnotationsMetaReader::canBeConstructed());

		$exception = null;
		try {
			new AnnotationsMetaReader();
		} catch (PackageRequired $exception) {
			// Beep
		}

		self::assertNotNull($exception);
		self::assertSame(['doctrine/annotations'], $exception->getPackages());
	}

}
