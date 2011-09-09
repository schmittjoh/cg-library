<?php

namespace CG\Tests\Generator;

use CG\Generator\PhpClass;

class PhpClassTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetName()
    {
        $class = new PhpClass();
        $this->assertNull($class->getName());

        $class = new PhpClass('foo');
        $this->assertEquals('foo', $class->getName());
        $this->assertSame($class, $class->setName('bar'));
        $this->assertEquals('bar', $class->getName());
    }

    public function testSetGetConstants()
    {
        $class = new PhpClass();

        $this->assertEquals(array(), $class->getConstants());
        $this->assertSame($class, $class->setConstants(array('foo' => 'bar')));
        $this->assertEquals(array('foo' => 'bar'), $class->getConstants());
        $this->assertSame($class, $class->setConstant('bar', 'baz'));
        $this->assertEquals(array('foo' => 'bar', 'bar' => 'baz'), $class->getConstants());
        $this->assertSame($class, $class->removeConstant('foo'));
        $this->assertEquals(array('bar' => 'baz'), $class->getConstants());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRemoveConstantThrowsExceptionWhenConstantDoesNotExist()
    {
        $class = new PhpClass();
        $class->removeConstant('foo');
    }

    public function testSetIsAbstract()
    {
        $class = new PhpClass();

        $this->assertFalse($class->isAbstract());
        $this->assertSame($class, $class->setAbstract(true));
        $this->assertTrue($class->isAbstract());
        $this->assertSame($class, $class->setAbstract(false));
        $this->assertFalse($class->isAbstract());
    }

    public function testSetIsFinal()
    {
        $class = new PhpClass();

        $this->assertFalse($class->isFinal());
        $this->assertSame($class, $class->setFinal(true));
        $this->assertTrue($class->isFinal());
        $this->assertSame($class, $class->setFinal(false));
        $this->assertFalse($class->isFinal());
    }
}