<?php

namespace EntitySerializer;

use Exception;
use ReflectionClass;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validation;


class EntityNormalizer implements NormalizerInterface, DenormalizerInterface
{
	private $validator;
	private $getSetNormalizer;
	private $builder;

	public function __construct()
	{
		$this->validator = Validation::createValidatorBuilder()
			->enableAnnotationMapping()
			->getValidator();
		$this->getSetNormalizer = new GetSetMethodNormalizer();
		$this->builder = new Builder();
	}

    public function normalize($object, $format = null, array $context = array())
	{
		return $this->getSetNormalizer($object, $format, $context);
	}

    public function denormalize($data, $class, $format = null, array $context = array())
	{
		switch($class) {
			case 'string':
				return (string) $data;
			case 'array':
				return (array) $data;
			default:
				return $this->denormalizeObject($data, $class, $format, $context);
		}
	}

    private function denormalizeObject($data, $class, $format = null, array $context = array())
	{
		$builder = $this->builder->newInstance($class);
		foreach($data as $name => $value) {
			$type = $this->getType($data, $name, $class);
			$value = $this->denormalize($value, $type, $format, $context);
			$builder->set($name, $value);
		}
		$entity = $builder->get();
		$violations = $this->validator->validate($entity);
		if(count($violations) != 0) {
			throw new ValidatorException('Error validation object');
		}
		return $entity;
	}

	private function getType($data, $name, $containerType)
	{
		$type = $this->tryGetTypeByContainerTypeHint($name, $containerType);
		if(null !== $type) {
			return $type;
		}
		return 'string';
	}

	private function tryGetTypeByContainerTypeHint($name, $containerType)
	{
		$reflector = new ReflectionClass($containerType);
		$paramaters = $reflector->getConstructor()->getParameters();
		foreach($paramaters as $paramater) {
			if($paramater->getName() == $name) {
				if($paramater->isArray()) {
					return 'array';
				}
				$class = $paramater->getClass();
				if(null !== $class) {
					return $class->getName();
				}
			}
		}
		return null;
	}
	

    public function supportsDenormalization($data, $type, $format = null)
	{
		return true;
	}

    public function supportsNormalization($data, $format = null)
	{
		return true;
	}
}
 
