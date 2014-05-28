<?php

namespace CG\Tests\Model;

use CG\Model\PhpProperty;
use CG\Model\PhpClass;
use CG\Model\PhpInterface;
use CG\Model\PhpMethod;
use CG\Model\PhpConstant;
use CG\Model\PhpTrait;
use CG\Model\PhpParameter;
use CG\Model\PhpFunction;
use gossi\docblock\tags\AuthorTag;
use gossi\docblock\tags\ThrowsTag;
use gossi\docblock\DocBlock;
use gossi\docblock\tags\SeeTag;
use gossi\docblock\tags\VarTag;

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
			->setDescription('my method')
			->setLongDescription('my very long method')
			->setType('string', 'this method returns a string')
			->addParameter(PhpParameter::create('a')
				->setDescription('method-param'));
	}
	
	private function getProperty() {
		return PhpProperty::create(self::PROP)
			->setDescription('my prop')
			->setLongDescription('my very long prop')
			->setType('int', 'this prop is an integer');
	}
	
	private function getConstant() {
		return PhpConstant::create(self::CONSTANT)
			->setDescription('my constant')
			->setLongDescription('my very long contstant')
			->setType('boolean', 'this constant is a boolean');
	}
	
	public function testClass() {
		$class = new PhpClass();
		$class
			->setName('class-name')
			->setDescription('this is my class')
			->setLongDescription('this is my very long class')
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
		
		$author = AuthorTag::create()
			->setName('gossi')
			->setEmail('iiih@mail.me')
		;
		$docblock->appendTag($author);
		
		$this->assertTrue($docblock->hasTag('author'));
		
		$expected = '/**
 * this is my class
 * 
 * this is my very long class
 * 
 * @author gossi <iiih@mail.me>
 */';
		$this->assertEquals($expected, $docblock->toString());
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
	
	public function testConstant() {
		$expected = '/**
 * my constant
 * 
 * my very long contstant
 * 
 * @var boolean this constant is a boolean
 */';
		$constant = $this->getConstant();
		$docblock = $constant->generateDocblock();
		
		$this->assertEquals($expected, ''.$docblock);
	}
	
	public function testProperty() {
		$expected = '/**
 * my prop
 * 
 * my very long prop
 * 
 * @var int this prop is an integer
 */';
		$constant = $this->getProperty();
		$docblock = $constant->generateDocblock();
		
		$this->assertEquals($expected, $docblock->toString());
	}
	
	public function testMethod() {
		$expected = '/**
 * my method
 * 
 * my very long method
 * 
 * @see MyClass#myMethod see-desc
 * @param mixed $a method-param
 * @throws \Exception when something goes wrong
 * @return string this method returns a string
 */';
		$throws = new ThrowsTag('\Exception when something goes wrong');
		$doc = new DocBlock();
		$doc->appendTag($throws);

		$method = $this->getMethod();
		$method->setDocblock($doc);
		$docblock = $method->generateDocblock();
		
		$see = new SeeTag('MyClass#myMethod see-desc');
		$docblock->appendTag($see);

		$this->assertSame($docblock, $doc);
		$this->assertEquals($expected, $docblock->toString());
	}
	
	public function testEmptyDocblock() {
		$docblock = new DocBlock();
		$this->assertEquals("/**\n */", $docblock->toString());
	}
	
	public function testObjectParam() {
		$expected = '/**
 * @param Request $r
 * @param mixed $a
 * @return Response this method returns a response object
 */';
		$function = PhpFunction::create(self::METHOD)
			->setType('Response', 'this method returns a response object')
			->addParameter(PhpParameter::create('r')->setType('Request'))
			->addParameter(PhpParameter::create('a')->setType('mixed'));
		
		$docblock = $function->generateDocblock();
		$this->assertNotNull($docblock);
		$this->assertSame($expected, $docblock->toString());
	}
}