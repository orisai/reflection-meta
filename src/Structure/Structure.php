<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\ReflectorSource;
use Reflector;

interface Structure
{

	public function getContextReflector(): Reflector;

	public function getSource(): ReflectorSource;

}
