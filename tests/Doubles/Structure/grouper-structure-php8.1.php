<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles\Structure\GrouperPHP81;

class GrouperStructureParent1PHP81
{

}

interface GrouperStructureInterface1PHP81
{

	public const B = 'b';

}

trait GrouperStructureTrait1PHP81
{

}

final class GrouperStructureDoublePHP81 extends GrouperStructureParent1PHP81 implements GrouperStructureInterface1PHP81
{

	use GrouperStructureTrait1PHP81;

	public const B = 'b';

}
