<?php

namespace EntitySerializer;

use Doctrine\Common\Annotations\AnnotationReader;
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
		$this->annotationReader = new AnnotationReader();
	}

    public function normalize($object, $format = null, array $context = array())
	{
		return $this->getSetNormalizer($object, $format, $context);
	}

    public function denormalize($data, $class, $format = null, array $context = array())
	{
		switch(true) {
			case 'string' === $class:
				return (string) $data;
			case 'array' === $class:
				return (array) $data;
			case preg_match('/^array<(?P<className>[^,><]+)>$/', $class, $match):
				return $this->denormalizeTypedArray($data, $match['className'], $format, $context);
			default:
				return $this->denormalizeObject($data, $class, $format, $context);
		}
	}

	private function denormalizeTypedArray($data, $class, $format = null, array $context = array())
	{
		$result = array();
		foreach($data as $element) {
			$result[] = $this->denormalizeObject($element, $class, $format, $context);
		}
		return $result;
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
		$type = $this->tryGetTypeByAnnotation($name, $containerType);
		if(null !== $type) {
			return $type;
		}
		$type = $this->tryGetTypeByContainerTypeHint($name, $containerType);
		if(null !== $type) {
			return $type;
		}
		return 'string';
	}

	private function tryGetTypeByAnnotation($name, $containerType)
	{
		$classReflector = new ReflectionClass($containerType);
		if(! $classReflector->hasProperty($name)) {
			return null;
		}
		$reflector = $classReflector->getProperty($name);
		$annotation = $this->annotationReader->getPropertyAnnotation($reflector, 'JMS\Serializer\Annotation\Type');
		if(null === $annotation) {
			return null;
		}
		return $annotation->name;
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
 
