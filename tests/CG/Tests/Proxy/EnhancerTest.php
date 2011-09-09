<?php

namespace CG\Tests\Proxy;

use CG\Proxy\LazyInitializerGenerator;

use CG\Proxy\Enhancer;

class EnhancerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getGenerationTests
     */
    public function testGenerateClass($class, $generatedClass, array $interfaces, array $generators)
    {
        $enhancer = new Enhancer(new \ReflectionClass($class), $interfaces, $generators);
        $enhancer->setNamingStrategy($this->getNamingStrategy($generatedClass));

        $this->assertEquals($this->getContent(substr($generatedClass, strrpos($generatedClass, '\\') + 1)), $enhancer->generateClass());
    }

    public function getGenerationTests()
    {
        return array(
            array('CG\Tests\Proxy\Fixture\SimpleClass', 'CG\Tests\Proxy\Fixture\EnhancedSimpleClass', array('CG\Tests\Proxy\Fixture\MarkerInterface'), array()),
            array('CG\Tests\Proxy\Fixture\SimpleClass', 'CG\Tests\Proxy\Fixture\SluggableSimpleClass', array('CG\Tests\Proxy\Fixture\SluggableInterface'), array()),
            array('CG\Tests\Proxy\Fixture\Entity', 'CG\Tests\Proxy\Fixture\LazyInitializingEntity', array(), array(
                new LazyInitializerGenerator(),
            ))
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