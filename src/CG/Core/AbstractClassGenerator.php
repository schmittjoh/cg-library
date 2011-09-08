<?php

namespace CG\Core;

use Zend\CodeGenerator\Php\PhpClass;

abstract class AbstractClassGenerator implements ClassGeneratorInterface
{
    private $namingStrategy;

    public function setNamingStrategy(NamingStrategyInterface $namingStrategy = null)
    {
        $this->namingStrategy = $namingStrategy;
    }

    protected function getClassName(\ReflectionClass $class)
    {
        if (null === $this->namingStrategy) {
            $this->namingStrategy = new DefaultNamingStrategy();
        }

        return $this->namingStrategy->getClassName($class);
    }
}