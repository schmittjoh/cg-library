<?php

namespace CG\Tests\Model;

use CG\Model\PhpParameter;
use CG\Model\PhpFunction;

class PhpFunctionTest extends \PHPUnit_Framework_TestCase {
	
	public function setUp() {
		// they are not explicitely instantiated through new WhatEver(); and such not
		// required through composer's autoload
		require_once __DIR__ . '/Fixture/function.php';
	}
	
	public function testFromReflection() {

		$function = new PhpFunction('fooBar');
		$function
			->addParameter(PhpParameter::create('baz')->setDefaultValue(null))
			->setBody('return \'wurst\';')
			->setDocblock('/**
 * Makes foo with bar
 * 
 * @param string $baz
 * @return string
 */');
		
		$this->assertEquals($function, PhpFunction::fromReflection(new \ReflectionFunction('fooBar')));
	}
}