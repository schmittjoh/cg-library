<?php

namespace CG\Tests\Generator;

use CG\Generator\PhpProperty;
use CG\Generator\PhpParameter;
use CG\Generator\PhpMethod;
use CG\Generator\PhpClass;

class PhpClassTest extends \PHPUnit_Framework_TestCase
{
    public function testFromReflection()
    {
        $class = new PhpClass();
        $class
            ->setName('CG\Tests\Generator\Fixture\Entity')
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
 */')->setBody(<<<'PHP'
$this->id = $a;
        if ($d === 'foo'){
            $this->enabled = true;
        }
PHP
)
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
            ->setBody("return 'abc';")
        );

        $class->setMethod(PhpMethod::create()
            ->setName('braceAbove')
            ->setVisibility('private')
            ->setBody(<<<'PHP'
$ret = $this->bar();
        return $ret;
PHP
        ));

        $class->setMethod(PhpMethod::create()
            ->setName('badFormat')
            ->setVisibility('private')
            ->setBody(<<<'PHP'
$ret = $this->bar();
             return $ret;
PHP
            ));

        $class->setMethod(PhpMethod::create()
            ->setName('oneLine')
            ->setVisibility('private')
            ->setBody("return 'abc';")
        );

        $class->setMethod(PhpMethod::create()
                ->setName('braceInComment')
                ->setVisibility('private')
                ->setBody("return 'abc';")
        );

        $this->assertEquals($class, PhpClass::fromReflection(new \ReflectionClass('CG\Tests\Generator\Fixture\Entity')));
    }

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

    public function testSetGetParentClassName()
    {
        $class = new PhpClass();

        $this->assertNull($class->getParentClassName());
        $this->assertSame($class, $class->setParentClassName('stdClass'));
        $this->assertEquals('stdClass', $class->getParentClassName());
        $this->assertSame($class, $class->setParentClassName(null));
        $this->assertNull($class->getParentClassName());
    }

    public function testSetGetInterfaceNames()
    {
        $class = new PhpClass();

        $this->assertEquals(array(), $class->getInterfaceNames());
        $this->assertSame($class, $class->setInterfaceNames(array('foo', 'bar')));
        $this->assertEquals(array('foo', 'bar'), $class->getInterfaceNames());
        $this->assertSame($class, $class->addInterfaceName('stdClass'));
        $this->assertEquals(array('foo', 'bar', 'stdClass'), $class->getInterfaceNames());
    }

    public function testSetGetUseStatements()
    {
        $class = new PhpClass();

        $this->assertEquals(array(), $class->getUseStatements());
        $this->assertSame($class, $class->setUseStatements(array('foo' => 'bar')));
        $this->assertEquals(array('foo' => 'bar'), $class->getUseStatements());
        $this->assertSame($class, $class->addUseStatement('Foo\Bar'));
        $this->assertEquals(array('foo' => 'bar', 'Bar' => 'Foo\Bar'), $class->getUseStatements());
        $this->assertSame($class, $class->addUseStatement('Foo\Bar', 'Baz'));
        $this->assertEquals(array('foo' => 'bar', 'Bar' => 'Foo\Bar', 'Baz' => 'Foo\Bar'), $class->getUseStatements());
    }

    /**
     * @dataProvider usesClassDataProvider
     */
    public function testUsesClass($usage, $typedef, $expected)
    {
        $class = new PhpClass();
        $class->addUseStatement($usage);
        $this->assertEquals($class->uses($typedef), $expected);
    }

    public function usesClassDataProvider()
    {
        return array(
            array('\DateTime', '\DateTime', false), // using fqdn from root ignores use statements
            array('\DateTime', 'DateTime', true),
            array('Foo\Bar\Baz', 'Baz', true),
            array('Foo\Bar\Baz', 'Bar', false),
            array('Foo\Bar\Baz', 'Foo', false),
            array('Foo\Bar', 'Bar\Baz', true),
            array('Foo\Bar', '\Bar\Baz', false)
        );
    }

    public function testSetGetProperties()
    {
        $class = new PhpClass();

        $this->assertEquals(array(), $class->getProperties());
        $this->assertSame($class, $class->setProperties($props = array('foo' => new PhpProperty())));
        $this->assertSame($props, $class->getProperties());
        $this->assertSame($class, $class->setProperty($prop = new PhpProperty('foo')));
        $this->assertSame(array('foo' => $prop), $class->getProperties());
        $this->assertTrue($class->hasProperty('foo'));
        $this->assertSame($class, $class->removeProperty('foo'));
        $this->assertEquals(array(), $class->getProperties());
    }

    public function testSetGetMethods()
    {
        $class = new PhpClass();

        $this->assertEquals(array(), $class->getMethods());
        $this->assertSame($class, $class->setMethods($methods = array('foo' => new PhpMethod())));
        $this->assertSame($methods, $class->getMethods());
        $this->assertSame($class, $class->setMethod($method = new PhpMethod('foo')));
        $this->assertSame(array('foo' => $method), $class->getMethods());
        $this->assertTrue($class->hasMethod('foo'));
        $this->assertSame($class, $class->removeMethod('foo'));
        $this->assertEquals(array(), $class->getMethods());
    }

    public function testSetGetDocblock()
    {
        $class = new PhpClass();

        $this->assertNull($class->getDocblock());
        $this->assertSame($class, $class->setDocblock('foo'));
        $this->assertEquals('foo', $class->getDocblock());
    }

    public function testSetGetRequiredFiles()
    {
        $class = new PhpClass();

        $this->assertEquals(array(), $class->getRequiredFiles());
        $this->assertSame($class, $class->setRequiredFiles(array('foo')));
        $this->assertEquals(array('foo'), $class->getRequiredFiles());
        $this->assertSame($class, $class->addRequiredFile('bar'));
        $this->assertEquals(array('foo', 'bar'), $class->getRequiredFiles());
    }
}
