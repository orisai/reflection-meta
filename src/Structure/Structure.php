<?php declare(strict_types = 1);

namespace Orisai\ReflectionMeta\Structure;

use Orisai\SourceMap\ReflectorSource;

interface Structure
{

	public function getSource(): ReflectorSource;

}
