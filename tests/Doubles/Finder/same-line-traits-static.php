<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder\SameLineTraitsStatic;

trait A1 {public static function a(): void{}} trait A2 {use A1;}

class SameLineTraitsStaticClass
{

	use A2;

}
