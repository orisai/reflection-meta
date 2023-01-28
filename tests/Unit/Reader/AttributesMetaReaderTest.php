<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Reader;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\ReflectionMeta\Reader\AttributesMetaReader;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\ReaderDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\TestAttribute;
use const PHP_VERSION_ID;

final class AttributesMetaReaderTest extends TestCase
{

	public function test(): void
	{
		if (PHP_VERSION_ID < 8_00_00) {
			self::markTestSkipped('Attributes are supported on PHP 8.0+');
		}

		require_once __DIR__ . '/../../Doubles/Reader/reader-combined.php';

		self::assertTrue(AttributesMetaReader::canBeConstructed());

		$reader = new AttributesMetaReader();
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
			$expected,
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
			$expected,
		);
	}

	public function testNotSupported(): void
	{
		if (PHP_VERSION_ID >= 8_00_00) {
			self::markTestSkipped('Attributes unavailability cannot be tested on PHP 8.0+');
		}

		self::assertFalse(AttributesMetaReader::canBeConstructed());

		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage('Attributes are supported since PHP 8.0');

		new AttributesMetaReader();
	}

}
