<?php

namespace EntitySerializer;

use ReflectionClass;


class Builder
{
	public function __construct()
	{
	}

	public function newInstance($className)
	{
		return new PartialBuild(new ReflectionClass($className));
	}
}
 
