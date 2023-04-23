<?php declare(strict_types = 1);

namespace Tests\Orisai\ReflectionMeta\Doubles;

final class DataCrate
{

	/** @var mixed */
	public $data;

	/**
	 * @param mixed $data
	 */
	public function __construct($data)
	{
		$this->data = $data;
	}

}
