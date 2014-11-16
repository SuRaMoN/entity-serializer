<?php

namespace EntitySerializer\TestClass;

use PHPUnit_Framework_TestCase;


class EntityWithTypeHintedProperties
{
	private $names;
	private $subEntity;

	public function __construct(array $names, EntityWithValidation $subEntity)
	{
		$this->names = $names;
		$this->subEntity = $subEntity;
	}

 	public function getNames()
 	{
 		return $this->names;
 	}

 	public function getSubEntity()
 	{
 		return $this->subEntity;
 	}
}
 
