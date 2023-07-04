<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Filter;

use function array_key_first;
use function array_key_last;
use function count;
use function explode;
use function implode;
use function preg_match;
use function preg_replace;
use const PHP_EOL;

final class AnnotationFilter
{

	public static function filterMultilineDocblock(string $docblock): string
	{
		// Split into lines, accepting any line endings
		$lines = explode(PHP_EOL, preg_replace('~\R~u', PHP_EOL, $docblock));

		// Ignore content of single line doc
		if (count($lines) === 1) {
			return $docblock;
		}

		// Remove first line if empty
		$firstLineKey = array_key_first($lines);
		$firstLine = $lines[$firstLineKey];
		if (preg_match('/^\s*$/', $firstLine) === 1) {
			unset($lines[$firstLineKey]);
		}

		// Remove last line if empty
		$lastLineKey = array_key_last($lines);
		$lastLine = $lines[$lastLineKey];
		if (preg_match('/^\s*$/', $lastLine) === 1) {
			unset($lines[$lastLineKey]);
		}

		// Remove a single asterisk and a single space from beginning of the line
		return preg_replace('#^ *\* ?#m', '', implode(PHP_EOL, $lines));
	}

}
