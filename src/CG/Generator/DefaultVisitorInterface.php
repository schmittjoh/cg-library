<?php

namespace CG\Generator;

interface DefaultVisitorInterface
{
    function reset();
    function startVisitingClass(PhpClass $class);
    function startVisitingConstants();
    function visitConstant($name, $value);
    function endVisitingConstants();
    function startVisitingProperties();
    function visitProperty(PhpProperty $property);
    function endVisitingProperties();
    function startVisitingMethods();
    function visitMethod(PhpMethod $method);
    function endVisitingMethods();
    function endVisitingClass(PhpClass $class);
}