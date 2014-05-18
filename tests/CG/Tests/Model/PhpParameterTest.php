<?php

namespace CG\Tests\Model;

use CG\Model\PhpParameter;

class PhpParameterTest extends \PHPUnit_Framework_TestCase
{
	public function setUp() {
		// they are not explicitely instantiated through new WhatEver(); and such not
		// required through composer's autoload
		require_once __DIR__ . '/Fixture/Entity.php';
	}
	
	public function testFromReflection() {
		$class = new \ReflectionClass('CG\Tests\Model\Fixture\Entity');
		$ctor = $class->getMethod('__construct');
		$params = $ctor->getParameters();
		
		foreach ($params as $param) {
			switch ($param->getName()) {
				case 'a': $this->paramA($param); break;
				case 'b': $this->paramB($param); break;
				case 'c': $this->paramC($param); break;
				case 'd': $this->paramD($param); break;
				case 'e': $this->paramE($param); break;
			}	
		}
	}
	
	private function paramA(\ReflectionParameter $param) {
		$param = PhpParameter::fromReflection($param);
		
		$this->assertEquals('a', $param->getName());
		$this->assertFalse($param->isPassedByReference());
		$this->assertEmpty($param->getDefaultValue());
		$this->assertEmpty($param->getType());
	}
	
	private function paramB(\ReflectionParameter $param) {
		$param = PhpParameter::fromReflection($param);
	
		$this->assertEquals('b', $param->getName());
		$this->assertTrue($param->isPassedByReference());
		$this->assertEmpty($param->getDefaultValue());
		$this->assertEquals('array', $param->getType());
	}
	
	private function paramC(\ReflectionParameter $param) {
		$param = PhpParameter::fromReflection($param);
	
		$this->assertEquals('c', $param->getName());
		$this->assertFalse($param->isPassedByReference());
		$this->assertEmpty($param->getDefaultValue());
		
		// PHP BUG ?: Doesn't return \stdClass, just stdClass
		// $this->assertEquals('\stdClass', $param->getType());
		$this->assertEquals('stdClass', $param->getType());
	}
	
	private function paramD(\ReflectionParameter $param) {
		$param = PhpParameter::fromReflection($param);
	
		$this->assertEquals('d', $param->getName());
		$this->assertFalse($param->isPassedByReference());
		$this->assertEquals('foo', $param->getDefaultValue());
		$this->assertEmpty($param->getType());
	}
	
	private function paramE(\ReflectionParameter $param) {
		$param = PhpParameter::fromReflection($param);
	
		$this->assertEquals('e', $param->getName());
		$this->assertFalse($param->isPassedByReference());
		$this->assertEmpty($param->getDefaultValue());
		$this->assertEquals('callable', $param->getType());
	}
	
    public function testType()
    {
        $param = new PhpParameter();

        $this->assertNull($param->getType());
        $this->assertSame($param, $param->setType('array'));
        $this->assertEquals('array', $param->getType());
        $this->assertSame($param, $param->setType('array', 'boo!'));
        $this->assertEquals('boo!', $param->getTypeDescription());
    }
}