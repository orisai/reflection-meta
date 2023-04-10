<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder\CompatiblePropertiesTraitsPHP81;

trait A1
{

	#[\Foo(bar: new \stdClass())]
	public string $a;

}

trait A2
{

	use A1;

}

trait B1
{

	#[\Foo(bar: new \stdClass())]
	public string $a;

}

class CompatiblePropertiesTraitsPHP81Class
{

	use A2, B1;

}