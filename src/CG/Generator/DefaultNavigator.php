<?php

namespace CG\Generator;

class DefaultNavigator
{
    public function accept(DefaultVisitorInterface $visitor, PhpClass $class)
    {
        $visitor->startVisitingClass($class);

        $constants = $class->getConstants();
        if (!empty($constants)) {
            $visitor->startVisitingConstants();
            foreach ($constants as $name => $value) {
                $visitor->visitConstant($name, $value);
            }
            $visitor->endVisitingConstants();
        }

        $properties = $class->getProperties();
        if (!empty($properties)) {
            $visitor->startVisitingProperties();
            foreach ($properties as $property) {
                $visitor->visitProperty($property);
            }
            $visitor->endVisitingProperties();
        }

        $methods = $class->getMethods();
        if (!empty($methods)) {
            $visitor->startVisitingMethods();
            foreach ($methods as $method) {
                $visitor->visitMethod($method);
            }
            $visitor->endVisitingMethods();
        }

        $visitor->endVisitingClass($class);
    }
}