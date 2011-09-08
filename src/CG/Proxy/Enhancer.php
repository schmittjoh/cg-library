<?php

namespace CG\Proxy;

use Zend\Reflection\ReflectionClass;
use CG\Generator\PhpMethod;
use CG\Generator\PhpDocblock;
use CG\Generator\PhpClass;
use CG\Core\AbstractClassGenerator;

class Enhancer extends AbstractClassGenerator
{
    private $generatedClass;
    private $class;
    private $interfaces;
    private $callbacks;
    private $interceptors;

    public function __construct(\ReflectionClass $class, array $interfaces = array(), array $interceptors = array(), array $callbacks = array())
    {
        if (empty($callbacks) && empty($interceptors) && empty($interfaces)) {
            throw new \RuntimeException('Either callbacks, or interceptors, or interfaces must be given.');
        }

        $this->class = $class;
        $this->interfaces = $interfaces;
        $this->interceptors = $interceptors;
        $this->callbacks = $callbacks;
    }

    public function createInstance(array $args = array())
    {
        $generatedClass = $this->getClassName($this->class);

        if (!class_exists($generatedClass, false)) {
            eval($this->generateClass());
        }

        $ref = new \ReflectionClass($generatedClass);

        return $ref->newInstanceArgs($args);
    }

    public final function generateClass()
    {
        static $docBlock;
        if (empty($docBlock)) {
            $docBlock = new PhpDocblock();
            $docBlock->setShortDescription('CG library enhanced proxy class.');
            $docBlock->setLongDescription('This code was generated automatically by the CG library, manual changes to it will be lost.');
        }

        $this->generatedClass = new PhpClass();
        $this->generatedClass->setDocblock($docBlock);
        $this->generatedClass->setName($this->getClassName($this->class));
        $this->generatedClass->setExtendedClass('\\'.$this->class->name);

        if (!empty($this->interfaces)) {
            $this->generatedClass->setImplementedInterfaces(array_map(function($v) { return '\\'.$v; }, $this->interfaces));

            foreach ($this->getInterfaceMethods() as $method) {
                $this->generatedClass->setMethod(PhpMethod::fromReflection($method));
            }
        }

        if (!empty($this->callbacks)) {

        }

        return $this->generatedClass->generate();
    }

    /**
     * Adds stub methods for the interfaces that have been implemented.
     */
    protected function getInterfaceMethods()
    {
        $methods = array();

        foreach ($this->interfaces as $interface) {
            $ref = new ReflectionClass($interface);
            $methods = array_merge($methods, $ref->getMethods());
        }

        return $methods;
    }
}