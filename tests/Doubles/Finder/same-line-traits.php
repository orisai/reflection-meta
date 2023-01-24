<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Finder\SameLineTraits;

trait A1 {public function a(): void{}} trait A2 {use A1;} trait A3 {use A2;} trait B1 {public function a(): void{}} trait C1 {public function c(): void{}}

class SameLineTraitsClass
{

	use C1, A3, B1 {
		A3::a insteadof B1;
	}

}
