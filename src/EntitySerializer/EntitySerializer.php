<?php

namespace EntitySerializer;

use Symfony\Component\Serializer\Serializer as SymfonySerializer;


class EntitySerializer extends SymfonySerializer
{
	public function __construct(array $normalizers = array(), array $encoders = array())
	{
		$normalizers = array_merge(
			new EntityNormalizer()
		);
		return parent::__construct($normalizers, $encoders);
	}
}
 
