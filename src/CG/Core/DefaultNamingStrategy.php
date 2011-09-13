<?php

namespace CG\Core;

/**
 * The default naming strategy.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class DefaultNamingStrategy implements NamingStrategyInterface
{
    public function getClassName(\ReflectionClass $class)
    {
        $userClass = ClassUtils::getUserClass($class->name);

        return $userClass.self::SEPARATOR.sha1($class->name);
    }
}