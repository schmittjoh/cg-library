<?php

namespace CG\Generator;

/**
 * The visitor interface required by the DefaultNavigator.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface DefaultVisitorInterface
{
    /**
     * Resets the visitors internal state to allow re-using the same instance.
     *
     * @return void
     */
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