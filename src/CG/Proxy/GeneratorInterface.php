<?php

namespace CG\Proxy;

use CG\Generator\PhpClass;

/**
 * Interface for enhancing generators.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface GeneratorInterface
{
    /**
     * Generates the necessary changes in the class.
     *
     * @param \ReflectionClass $originalClass
     * @param PhpClass $generatedClass The generated class
     * @return void
     */
    function generate(\ReflectionClass $originalClass, PhpClass $generatedClass);
}