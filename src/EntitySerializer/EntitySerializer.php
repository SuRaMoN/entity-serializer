<?php

namespace EntitySerializer;

use Symfony\Component\Serializer\Serializer as SymfonySerializer;


class EntitySerializer extends SymfonySerializer
{
	public function __construct(array $normalizers = array(), array $encoders = array())
	{
		$normalizers = array_merge($normalizers, array(new EntityNormalizer()));
		$encoders = array_merge($encoders, array(
			new \Symfony\Component\Serializer\Encoder\JsonEncoder(),
			new \Symfony\Component\Serializer\Encoder\XmlEncoder(),
			new YamlEncoder(),
		));
		return parent::__construct($normalizers, $encoders);
	}
}
 
