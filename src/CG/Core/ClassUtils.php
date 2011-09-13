<?php

namespace CG\Core;

use CG\Proxy\Enhancer;

abstract class ClassUtils
{
    public static function getUserClass($className)
    {
        if (false === $pos = strpos($className, NamingStrategyInterface::SEPARATOR)) {
            return $className;
        }

        return substr($className, 0, $pos);
    }

    private final function __construct() {}
}