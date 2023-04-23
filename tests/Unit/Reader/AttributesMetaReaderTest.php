<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Unit\Reader;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\ReflectionMeta\Reader\AttributesMetaReader;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\BaseTestAttribute;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\ReaderDouble;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\TestAttribute1;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\TestAttribute2;
use Tests\Orisai\ReflectionMeta\Doubles\Structure\Reader\ReaderCombined\UnusedAttribute;
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

		$constant = $class->getReflectionConstant('A');
		self::assertEquals(
			$reader->readConstant($constant, BaseTestAttribute::class),
			$expected,
		);
		self::assertEquals(
			$reader->readConstant($constant, TestAttribute2::class),
			$expected2,
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
			$expected,
		);
		self::assertEquals(
			$reader->readParameter($parameter, TestAttribute2::class),
			$expected2,
		);
		self::assertEquals(
			$reader->readParameter($parameter, UnusedAttribute::class),
			[],
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
