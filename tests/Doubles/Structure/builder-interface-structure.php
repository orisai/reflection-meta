<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\Interfaces;

interface BuilderInterfaceDoubleInterface1
{

}

interface BuilderInterfaceDoubleInterface2 extends BuilderInterfaceDoubleInterface1
{

}

interface BuilderInterfaceDoubleInterface3 extends BuilderInterfaceDoubleInterface1
{

}

final class BuilderInterfaceDouble implements BuilderInterfaceDoubleInterface2, BuilderInterfaceDoubleInterface3
{

}
