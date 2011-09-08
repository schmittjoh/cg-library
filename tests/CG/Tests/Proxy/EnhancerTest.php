<?php

namespace CG\Tests\Proxy;

use CG\Proxy\Enhancer;

class EnhancerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getGenerationTests
     */
    public function testGenerateClass($class, array $interfaces, $generatedClass)
    {
        $enhancer = new Enhancer(new \ReflectionClass($class), $interfaces);
        $enhancer->setNamingStrategy($this->getNamingStrategy($generatedClass));

        $this->assertEquals($this->getContent(basename($generatedClass)), $enhancer->generateClass());
    }

    public function getGenerationTests()
    {
        return array(
            array('CG\Tests\Proxy\Fixture\SimpleClass', array('CG\Tests\Proxy\Fixture\MarkerInterface'), 'CG\Tests\Proxy\Fixture\EnhancedSimpleClass'),
            array('CG\Tests\Proxy\Fixture\SimpleClass', array('CG\Tests\Proxy\Fixture\SluggableInterface'), 'CG\Tests\Proxy\Fixture\SluggableSimpleClass'),
        );
    }

    private function getContent($file)
    {
        return file_get_contents(__DIR__.'/Fixture/generated/'.$file.'.php.gen');
    }

    private function getNamingStrategy($name)
    {
        $namingStrategy = $this->getMock('CG\Core\NamingStrategyInterface');
        $namingStrategy
            ->expects($this->any())
            ->method('getClassName')
            ->will($this->returnValue($name))
        ;

        return $namingStrategy;
    }
}