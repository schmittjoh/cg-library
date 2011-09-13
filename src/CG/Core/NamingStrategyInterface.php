<?php

namespace CG\Core;

/**
 * The naming strategy interface.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface NamingStrategyInterface
{
    const SEPARATOR = '__CG__';

    /**
     * Returns the class name for the proxy class.
     *
     * The generated class name MUST be the original class appended with the
     * separator, and an optional string that is up to the implementation.
     *
     * @param \ReflectionClass $class
     * @return string the class name for the generated class
     */
    function getClassName(\ReflectionClass $class);
}