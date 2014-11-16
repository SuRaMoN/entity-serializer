<?php

namespace EntitySerializer\TestClass;


class SimpleEntityWithOnlyStringProperties
{
	private $name;
	private $description;
	private $location;

	public function __construct($name, $description, $location = '')
	{
		$this->name = $name;
		$this->description = $description;
		$this->location = $location;
	}
 
 	public function getLocation()
 	{
 		return $this->location;
 	}
 
 	public function getDescription()
 	{
 		return $this->description;
 	}
 
 	public function getName()
 	{
 		return $this->name;
 	}
}
 
