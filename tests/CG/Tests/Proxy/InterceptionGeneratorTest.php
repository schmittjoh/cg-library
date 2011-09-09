<?php

namespace CG\Tests\Proxy;

use CG\Proxy\InterceptionGenerator;
use CG\Generator\PhpClass;
use CG\Tests\Proxy\Fixture\TraceInterceptor;

class InterceptionGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerate()
    {
        $genClass = new PhpClass();
        $genClass->setExtendedClass('\CG\Tests\Proxy\Fixture\Entity');

        $interception = new InterceptionGenerator();
        $interception->setPrefix('');

        $genClass->setName($name = 'CG\Tests\Proxy\Fixture\TraceableEntity'.sha1(microtime(true)));
        $interception->generate(new \ReflectionClass('CG\Tests\Proxy\Fixture\Entity'), $genClass);
        eval($code = $genClass->generate());

        $traceable = new $name();
        $traceable->setLoader($this->getLoader(array(
            $interceptor1 = new TraceInterceptor(),
            $interceptor2 = new TraceInterceptor(),
        )));

        $this->assertEquals('foo', $traceable->getName());
        $this->assertEquals('foo', $traceable->getName());
        $this->assertEquals(2, count($interceptor1->getLog()));
        $this->assertEquals(2, count($interceptor2->getLog()));
    }

    private function getLoader(array $interceptors)
    {
        $loader = $this->getMock('CG\Proxy\InterceptorLoaderInterface');
        $loader
            ->expects($this->any())
            ->method('loadInterceptors')
            ->will($this->returnValue($interceptors))
        ;

        return $loader;
    }
}
