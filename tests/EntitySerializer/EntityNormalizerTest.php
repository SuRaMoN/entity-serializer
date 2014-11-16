<?php

namespace EntitySerializer;

use PHPUnit_Framework_TestCase;


class EntityNormalizerTest extends PHPUnit_Framework_TestCase
{
	private function createNormalizer()
	{
	    return new EntityNormalizer();
	}
	
	/** @test */
	public function testDenormalizeSimpleEntityWithOnlyStringProperties()
	{
		$data = array(
			'name' => 'john',
			'description' => 'developer',
			'location' => 'belgium',
		);
		$entity = $this->createNormalizer()->denormalize($data, 'EntitySerializer\TestClass\SimpleEntityWithOnlyStringProperties');
		$this->assertEquals('john', $entity->getName());
		$this->assertEquals('developer', $entity->getDescription());
		$this->assertEquals('belgium', $entity->getLocation());
	}

	/**
	 * @test
	 * @expectedException RuntimeException
	 */
	public function testDenormalizeWithNotEnoughDataThrowsException()
	{
		$data = array(
			'name' => 'john',
		);
		$this->createNormalizer()->denormalize($data, 'EntitySerializer\TestClass\SimpleEntityWithOnlyStringProperties');
	}

	/**
	 * @test
	 * @expectedException RuntimeException
	 */
	public function testDenormalizeWithWrongDataThrowsException()
	{
		$data = array(
			'name' => 'john',
			'description' => 'developer',
			'location' => 'belgium',
			'wrongwrongwrong' => 'idd',
		);
		$this->createNormalizer()->denormalize($data, 'EntitySerializer\TestClass\SimpleEntityWithOnlyStringProperties');
	}

	/**
	 * @test
	 * @expectedException Symfony\Component\Validator\Exception\ValidatorException
	 */
	public function testDenormalizeWithValidationErrorsThrowsException()
	{
		$data = array('name' => '');
		$this->createNormalizer()->denormalize($data, 'EntitySerializer\TestClass\EntityWithValidation');
	}

	/** @test */
	public function testNormalizeEntityWithTypeHintedProperties()
	{
		$data = array(
			'names' => array('john', 'aliza'),
			'subEntity' => array(
				'name' => 'john',
			),
		);
		$entity = $this->createNormalizer()->denormalize($data, 'EntitySerializer\TestClass\EntityWithTypeHintedProperties');
		$this->assertEquals(array('john', 'aliza'), $entity->getNames());
		$this->assertEquals('john', $entity->getSubEntity()->getName());
	}
}

