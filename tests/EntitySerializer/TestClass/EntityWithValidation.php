<?php

namespace EntitySerializer\TestClass;

use Symfony\Component\Validator\Constraints as Assert;


class EntityWithValidation
{
	/**
     * @Assert\NotBlank
	 */
	private $name;

	public function __construct($name)
	{
		$this->name = $name;
	}

 	public function getName()
 	{
 		return $this->name;
 	}
}
 
