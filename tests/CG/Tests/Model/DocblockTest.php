<?php

namespace CG\Tests\Generator;

use CG\Model\PhpProperty;
use CG\Model\PhpClass;
use CG\Model\PhpInterface;
use CG\Model\PhpMethod;
use CG\Model\PhpConstant;
use CG\Model\PhpTrait;
use CG\Model\PhpParameter;
use CG\Model\PhpFunction;

class DocblockTest extends \PHPUnit_Framework_TestCase
{

	const METHOD = 'myMethod';
	const PROP = 'myProperty';
	const CONSTANT = 'MY_CONSTANT';

	/**
	 * @return PhpMethod
	 */
	private function getMethod() {
		return PhpMethod::create(self::METHOD)
			->setType('string', 'this method returns a string')
			->addParameter(new PhpParameter('a'));
	}
	
	private function getProperty() {
		return PhpProperty::create(self::PROP)
			->setType('int', 'this prop is an integer');
	}
	
	private function getConstant() {
		return PhpConstant::create(self::CONSTANT)
			->setType('boolean', 'this constant is a boolean');
	}
	
	public function testClass() {
		$class = new PhpClass();
		$class
			->setName('class-name')
			->setDescription('this is my class')
			->setLongDescription('this is my very long description')
			->setProperty($this->getProperty())
			->setMethod($this->getMethod())
			->setConstant($this->getConstant())
		;
		
		$docblock = $class->generateDocblock();
		$this->assertNotNull($docblock);
		$this->assertSame($docblock, $class->getDocblock());
		$this->assertNotNull($class->getProperty(self::PROP)->getDocblock());
		$this->assertNotNull($class->getMethod(self::METHOD)->getDocblock());
		$this->assertNotNull($class->getConstant(self::CONSTANT)->getDocblock());
	}
	
	public function testInterface() {
		$interface = new PhpInterface();
		$interface
			->setDescription('my interface')
			->setLongDescription('this is my very long description')
			->setConstant($this->getConstant())
			->setMethod($this->getMethod())
		;
		
		$docblock = $interface->generateDocblock();
		$this->assertNotNull($docblock);
		$this->assertSame($docblock, $interface->getDocblock());
		$this->assertNotNull($interface->getMethod(self::METHOD)->getDocblock());
		$this->assertNotNull($interface->getConstant(self::CONSTANT)->getDocblock());
	}
	
	public function testTrait() {
		$trait = new PhpTrait();
		$trait
			->setDescription('my trait')
			->setLongDescription('this is my very long description')
			->setProperty($this->getProperty())
			->setMethod($this->getMethod())
		;
	
		$docblock = $trait->generateDocblock();
		$this->assertNotNull($docblock);
		$this->assertSame($docblock, $trait->getDocblock());
		$this->assertNotNull($trait->getProperty(self::PROP)->getDocblock());
		$this->assertNotNull($trait->getMethod(self::METHOD)->getDocblock());
	}
	
	public function testFunction() {
		$function = PhpFunction::create(self::METHOD)
			->setType('string', 'this method returns a string')
			->addParameter(new PhpParameter('a'));
		
		$docblock = $function->generateDocblock();
		$this->assertNotNull($docblock);
		$this->assertSame($docblock, $function->getDocblock());
	}
}