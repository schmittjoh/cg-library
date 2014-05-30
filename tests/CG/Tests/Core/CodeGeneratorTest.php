<?php

namespace CG\Tests\Core;

use CG\Core\DefaultGeneratorStrategy;
use CG\Model\PhpProperty;
use CG\Model\PhpMethod;
use CG\Model\PhpClass;
use CG\Core\CodeGenerator;
use CG\Model\PhpFunction;
use CG\Model\PhpParameter;

class CodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
	public function testGeneratorWithComments() {
		$codegen = new CodeGenerator();
		$code = $codegen->generateCode($this->getClass());
		
		$this->assertEquals($code, $this->getContent('CommentedGenerationTestClass.php'));
	}
	
	public function testGenerator() {
		$codegen = new CodeGenerator();
		$codegen->setGenerateDocblock(false);
		$code = $codegen->generateCode($this->getClass());
		
		$this->assertEquals($code, $this->getContent('GenerationTestClass_A.php'));
	}

	/**
	 * @param string $file
	 */
	private function getContent($file) {
		return file_get_contents(__DIR__.'/generated/'.$file);
	}

	/**
	 * @return PhpClass
	 */
	private function getClass() {
        $class = PhpClass::create()
            ->setName('GenerationTestClass')
            ->setMethod(PhpMethod::create('a'))
            ->setMethod(PhpMethod::create('b'))
            ->setProperty(PhpProperty::create('a'))
            ->setProperty(PhpProperty::create('b'))
            ->setConstant('a', 'foo')
            ->setConstant('b', 'bar')
        ;

        return $class;
	}
	
	public function testPrimitveParameter() {
		$expected = 'function fn($a)
{
}';
		$fn = PhpFunction::create('fn')
			->addParameter(PhpParameter::create('a')->setType('int'))
		;
		
		$codegen = new CodeGenerator();
		$codegen->setGenerateDocblock(false);
		$code = $codegen->generateCode($fn);
		
		$this->assertEquals($expected, $code);
	}

	public function testNonPrimitveParameter() {
		$expected = 'function fn(Response $a)
{
}';
		$fn = PhpFunction::create('fn')
		->addParameter(PhpParameter::create('a')->setType('Response'))
		;
	
		$codegen = new CodeGenerator();
		$codegen->setGenerateDocblock(false);
		$code = $codegen->generateCode($fn);
	
		$this->assertEquals($expected, $code);
	}
	
}
