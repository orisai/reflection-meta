<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder\IncompatiblePropertiesTraitsPHP81;

use Foo;
use Tests\Orisai\ReflectionMeta\Doubles\DataCrate;

trait A1
{

	#[Foo(bar: new DataCrate('a1'))]
	public string $a;

}

trait A2
{

	use A1;

	#[Foo(bar: new DataCrate('a2'))]
	public string $a;

}

trait B1
{

	#[Foo(bar: new DataCrate('b1'))]
	public string $a;

}

class IncompatiblePropertiesTraitsPHP81Class
{

	use A2;
	use B1;

}
