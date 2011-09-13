<?php

namespace CG\Core;

class DefaultNamingStrategy implements NamingStrategyInterface
{
    public function getClassName(\ReflectionClass $class)
    {
        $userClass = ClassUtils::getUserClass($class->name);

        return $userClass.self::SEPARATOR.sha1($class->name);
    }
}