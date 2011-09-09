<?php

namespace CG\Tests\Proxy;

use CG\Proxy\LazyInitializerInterface;
use CG\Proxy\LazyInitializerGenerator;
use CG\Generator\PhpClass;

class LazyInitializerGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $class = new \ReflectionClass('CG\Tests\Proxy\Fixture\Entity');
        $genClass = new PhpClass();

        $generator = new LazyInitializerGenerator();
        $generator->setPrefix('');
        $generator->generate($class, $genClass);

        $genClass->setName($name = 'Entity'.sha1(microtime(true)));
        eval($code = $genClass->generate());

        $entity = new $name();
        $entity->setLazyInitializer($initializer = new Initializer());
        $this->assertEquals('foo', $entity->getName());
        $this->assertSame($entity, $initializer->getLastObject());
    }
}

class Initializer implements LazyInitializerInterface
{
    private $lastObject;

    public function initializeObject($object)
    {
        $this->lastObject = $object;
    }

    public function getLastObject()
    {
        return $this->lastObject;
    }
}