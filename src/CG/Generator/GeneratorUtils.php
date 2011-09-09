<?php

namespace CG\Generator;

/**
 * Some Generator utils.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
abstract class GeneratorUtils
{
    private final function __construct() {}

    public static function callMethod(\ReflectionMethod $method, array $params = null)
    {
        if (null === $params) {
            $params = array_map(function($p) { return '$'.$p->name; }, $method->getParameters());
        }

        return '\\'.$method->getDeclaringClass()->name.'::'.$method->name.'('.implode(', ', $params).')';
    }
}