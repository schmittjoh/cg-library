<?php

namespace CG\Tests\Utils;

use CG\Utils\ReflectionUtils;
class ReflectionUtilsTest extends \PHPUnit_Framework_TestCase {
	
	public function setUp() {
		// they are not explicitely instantiated through new WhatEver(); and such not
		// required through composer's autoload
		require_once __DIR__ . '/Fixture/functions.php';
	}
	
	public function testFunctionBody() {
		$actual = ReflectionUtils::getFunctionBody(new \ReflectionFunction('wurst'));
		$expected = 'return \'wurst\';';
		
		$this->assertEquals($expected, $actual);
		
		$actual = ReflectionUtils::getFunctionBody(new \ReflectionFunction('inline'));
		$expected = 'return \'x\';';
		
		$this->assertEquals($expected, $actual);
	}
}