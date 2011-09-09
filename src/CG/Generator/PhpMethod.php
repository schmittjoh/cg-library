<?php

namespace CG\Generator;

use Zend\Reflection\ReflectionMethod as ZendReflectionMethod;
use Zend\CodeGenerator\Php\PhpMethod as ZendPhpMethod;

class PhpMethod extends ZendPhpMethod
{
    public static function fromReflection(\ReflectionMethod $method)
    {
        if (!$method instanceof ZendReflectionMethod) {
            $method = new ZendReflectionMethod($method->class, $method->name);
        }

        return ZendPhpMethod::fromReflection($method);
    }
}