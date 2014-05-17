<?php

namespace CG\Tests\Model;

use CG\Model\PhpProperty;
use CG\Model\PhpParameter;
use CG\Model\PhpMethod;
use CG\Model\PhpClass;
use CG\Tests\Model\Fixture\Entity;

class PhpClassTest extends \PHPUnit_Framework_TestCase
{
	public function setUp() {
		// they are not explicitely instantiated through new WhatEver(); and such not
		// required through composer's autoload
		require_once __DIR__ . '/Fixture/Entity.php';
	}
	
    public function testFromReflection()
    {
        $class = new PhpClass();
        $class
            ->setQualifiedName('CG\Tests\Model\Fixture\Entity')
            ->setAbstract(true)
            ->setDocblock('/**
 * Doc Comment.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */')
             ->setProperty(PhpProperty::create('id')
                 ->setVisibility('private')
                 ->setDocblock('/**
 * @var integer
 */')
             )
             ->setProperty(PhpProperty::create('enabled')
                 ->setVisibility('private')
                 ->setDefaultValue(false)
             )
        ;

        $method = PhpMethod::create()
            ->setName('__construct')
            ->setFinal(true)
            ->addParameter(new PhpParameter('a'))
            ->addParameter(PhpParameter::create()
                ->setName('b')
                ->setType('array')
                ->setPassedByReference(true)
            )
            ->addParameter(PhpParameter::create()
                ->setName('c')
                ->setType('stdClass')
            )
            ->addParameter(PhpParameter::create()
                ->setName('d')
                ->setDefaultValue('foo')
            )->setDocblock('/**
 * Another doc comment.
 *
 * @param unknown_type $a
 * @param array        $b
 * @param \stdClass    $c
 * @param string       $d
 */')
        ;
        $class->setMethod($method);

        $class->setMethod(PhpMethod::create()
            ->setName('foo')
            ->setAbstract(true)
            ->setVisibility('protected')
        );

        $class->setMethod(PhpMethod::create()
            ->setName('bar')
            ->setStatic(true)
            ->setVisibility('private')
        );

        $this->assertEquals($class, PhpClass::fromReflection(new \ReflectionClass('CG\Tests\Model\Fixture\Entity')));
    }

    public function testConstants()
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

    public function testAbstract()
    {
        $class = new PhpClass();

        $this->assertFalse($class->isAbstract());
        $this->assertSame($class, $class->setAbstract(true));
        $this->assertTrue($class->isAbstract());
        $this->assertSame($class, $class->setAbstract(false));
        $this->assertFalse($class->isAbstract());
    }

    public function testFinal()
    {
        $class = new PhpClass();

        $this->assertFalse($class->isFinal());
        $this->assertSame($class, $class->setFinal(true));
        $this->assertTrue($class->isFinal());
        $this->assertSame($class, $class->setFinal(false));
        $this->assertFalse($class->isFinal());
    }

    public function testParentClassName()
    {
        $class = new PhpClass();

        $this->assertNull($class->getParentClassName());
        $this->assertSame($class, $class->setParentClassName('stdClass'));
        $this->assertEquals('stdClass', $class->getParentClassName());
        $this->assertSame($class, $class->setParentClassName(null));
        $this->assertNull($class->getParentClassName());
    }

    public function testInterfaces()
    {
        $class = new PhpClass();

        $this->assertEquals(array(), $class->getInterfaces());
        $this->assertSame($class, $class->setInterfaces(array('foo', 'bar')));
        $this->assertEquals(array('foo', 'bar'), $class->getInterfaces());
        $this->assertSame($class, $class->addInterface('stdClass'));
        $this->assertEquals(array('foo', 'bar', 'stdClass'), $class->getInterfaces());
    }

    public function testProperties()
    {
        $class = new PhpClass();

        $this->assertEquals(array(), $class->getProperties());
        $this->assertSame($class, $class->setProperty($prop = new PhpProperty('foo')));
        $this->assertSame(array('foo' => $prop), $class->getProperties());
        $this->assertTrue($class->hasProperty('foo'));
        $this->assertSame($class, $class->removeProperty('foo'));
        $this->assertEquals(array(), $class->getProperties());
    }
}
