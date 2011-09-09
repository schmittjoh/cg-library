<?php

namespace CG\Core;

use CG\Generator\PhpClass;

/**
 * Abstract base class for all class generators.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
abstract class AbstractClassGenerator implements ClassGeneratorInterface
{
    private $namingStrategy;
    private $generatorStrategy;

    public function setNamingStrategy(NamingStrategyInterface $namingStrategy = null)
    {
        $this->namingStrategy = $namingStrategy;
    }

    public function setGeneratorStrategy(GeneratorStrategyInterface $generatorStrategy = null)
    {
        $this->generatorStrategy = $generatorStrategy;
    }

    public function getClassName(\ReflectionClass $class)
    {
        if (null === $this->namingStrategy) {
            $this->namingStrategy = new DefaultNamingStrategy();
        }

        return $this->namingStrategy->getClassName($class);
    }

    protected function generateCode(PhpClass $class)
    {
        if (null === $this->generatorStrategy) {
            $this->generatorStrategy = new DefaultGeneratorStrategy();
        }

        return $this->generatorStrategy->generate($class);
    }
}