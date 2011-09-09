<?php

namespace CG\Core;

use CG\Generator\PhpClass;

interface GeneratorStrategyInterface
{
    function generate(PhpClass $class);
}