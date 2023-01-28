<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Reader;

use Doctrine\Common\Annotations\AnnotationReader;
use Orisai\ReflectionMeta\Reader\AnnotationsMetaReader;
use Orisai\Utils\Dependencies\DependenciesTester;
use Orisai\Utils\Dependencies\Exception\PackageRequired;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\ReaderDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\TestAttribute;

final class AnnotationsMetaReaderTest extends TestCase
{

	public function test(): void
	{
		require_once __DIR__ . '/../../Doubles/Reader/reader-combined.php';

		$reader = new AnnotationsMetaReader();
		$class = new ReflectionClass(ReaderDouble::class);
		$expected = [
			new TestAttribute(),
			new TestAttribute(),
		];

		self::assertEquals(
			$reader->readClass($class),
			$expected,
		);

		self::assertEquals(
			$reader->readClassConstant($class->getReflectionConstant('A')),
			[],
		);

		self::assertEquals(
			$reader->readProperty($class->getProperty('a')),
			$expected,
		);

		$method = $class->getMethod('a');
		self::assertEquals(
			$reader->readMethod($method),
			$expected,
		);

		self::assertEquals(
			$reader->readParameter($method->getParameters()[0]),
			[],
		);
	}

	public function testDependency(): void
	{
		require_once __DIR__ . '/../../Doubles/Reader/reader-combined.php';

		$reader = new AnnotationsMetaReader(new AnnotationReader());
		$class = new ReflectionClass(ReaderDouble::class);
		$expected = [
			new TestAttribute(),
			new TestAttribute(),
		];

		self::assertEquals(
			$reader->readClass($class),
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
