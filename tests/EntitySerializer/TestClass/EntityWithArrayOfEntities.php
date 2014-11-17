<?php

namespace EntitySerializer\TestClass;

use JMS\Serializer\Annotation as JMS;


class EntityWithArrayOfEntities
{
	/**
	 * @JMS\Type("array<EntitySerializer\TestClass\EntityWithValidation>")
	 */
	private $subEntities;

	public function __construct(array $subEntities)
	{
		$this->subEntities = $subEntities;
	}

 	public function getSubEntities()
 	{
 		return $this->subEntities;
 	}
}
 
