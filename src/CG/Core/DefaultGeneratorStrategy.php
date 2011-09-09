<?php

namespace CG\Core;

use CG\Generator\PhpClass;
use CG\Generator\DefaultVisitor;
use CG\Generator\DefaultNavigator;

class DefaultGeneratorStrategy implements GeneratorStrategyInterface
{
    public function generate(PhpClass $class)
    {
        static $navigator;

        if (empty($navigator)) {
            $navigator = new DefaultNavigator();
        }

        $visitor = new DefaultVisitor();
        $navigator->accept($visitor, $class);

        return $visitor->getContent();
    }
}