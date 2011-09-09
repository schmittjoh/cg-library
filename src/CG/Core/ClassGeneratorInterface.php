<?php

namespace CG\Core;

use CG\Generator\PhpClass;

/**
 * Interface for class generators.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface ClassGeneratorInterface
{
    /**
     * Generates the PHP class.
     *
     * @return string
     */
    function generateClass();
}