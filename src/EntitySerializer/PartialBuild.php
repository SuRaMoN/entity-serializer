<?php

namespace EntitySerializer;

use Exception;
use ReflectionClass;
use RuntimeException;


class PartialBuild
{
	private $reflector;
	private $cachedConstructorParameters = array();
	private $cachedRequiredConstructorParameters = array();
	private $parameters = array();

	public function __construct(ReflectionClass $reflector)
	{
		$this->reflector = $reflector;
		foreach($reflector->getConstructor()->getParameters() as $parameter) {
			$this->cachedConstructorParameters[$parameter->getName()] = true;
			if(! $parameter->isDefaultValueAvailable()) {
				$this->cachedRequiredConstructorParameters[$parameter->getName()] = true;
			}
		}
	}

	public function get()
	{
		$unspecifiedParameters = array_keys(array_diff_key($this->cachedRequiredConstructorParameters, $this->parameters));
		if(count($unspecifiedParameters) != 0) {
			throw new RuntimeException('Some required parameters were not specified: ' . implode(', ', $unspecifiedParameters));
		}
		$parameters = array();
		foreach($this->cachedConstructorParameters as $name => $_) {
			if(! array_key_exists($name, $this->parameters)) {
				break;
			}
			$parameters[] = $this->parameters[$name];
		}
		return $this->reflector->newInstanceArgs($parameters);
	}

	public function __call($name, $parameters)
	{
		if(strpos($name, 'set') !== 0) {
			throw new Exception("Unknown function: $name");
		}
		$name = lcfirst(substr($name, 3));
		if(count($parameters) != 1) {
			throw new Exception("Can only pass one parameter as value");
		}
		return $this->set($name, reset($parameters));
	}

	public function set($name, $value)
	{
		if(! array_key_exists($name, $this->cachedConstructorParameters)) {
			throw new RuntimeException("Trying to set unknown parameter: '$name'");
		}
		$this->parameters[$name] = $value;
		return $this;
	}
}

