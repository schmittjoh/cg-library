<?php

namespace CG\Tests\Model;

use CG\Model\PhpClass;

class AbstractModelTest extends \PHPUnit_Framework_TestCase
{
    public function testUseStatements()
    {
        $class = new PhpClass();

        $this->assertEquals([], $class->getAttributes());
        $this->assertSame($class, $class->setAttributes(['foo' => 'bar']));
        $this->assertEquals(['foo' => 'bar'], $class->getAttributes());
        $this->assertSame($class, $class->setAttribute('key', 'val'));
        $this->assertEquals(['foo' => 'bar', 'key' => 'val'], $class->getAttributes());
        $this->assertTrue($class->hasAttribute('foo'));
        $this->assertSame('bar', $class->removeAttribute('foo'));
        $this->assertEquals(['key' => 'val'], $class->getAttributes());
        $this->assertTrue($class->hasAttribute('key'));
        $this->assertEquals('val', $class->getAttribute('key'));
        $this->assertEquals('bar', $class->getAttributeOrElse('foo', 'bar'));
        $this->assertEquals('val', $class->getAttributeOrElse('key', 'bar'));
    }
   
	/**
     * @expectedException \InvalidArgumentException
     */
    public function testNonExistentGetAttribute()
    {
    	$class = new PhpClass();
    	$class->getAttribute('nope');
    }

}
