<?php

namespace CG\Core;

class DefaultNamingStrategy implements NamingStrategyInterface
{
    const SEPARATOR = '__CG__';

    public function getClassName(\ReflectionClass $class)
    {
        return $class->name.self::SEPARATOR.sha1($class->name.spl_object_hash($class));
    }
}