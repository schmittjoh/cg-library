<?php

namespace CG\Proxy;

use CG\Generator\PhpClass;

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