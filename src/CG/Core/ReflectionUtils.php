<?php

namespace CG\Core;

abstract class ReflectionUtils
{
    public static function getOverrideableMethods(\ReflectionClass $class, $publicOnly = false)
    {
        $filter = \ReflectionMethod::IS_PUBLIC;

        if (!$publicOnly) {
            $filter |= \ReflectionMethod::IS_PROTECTED;
        }

        return array_filter(
            $class->getMethods($filter),
            function($method) { return !$method->isFinal(); }
        );
    }

    private final function __construct() { }
}