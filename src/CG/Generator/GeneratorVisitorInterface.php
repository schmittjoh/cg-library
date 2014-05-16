<?php

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace CG\Generator;

use CG\Model\PhpConstant;
use CG\Model\PhpProperty;
use CG\Model\PhpMethod;
use CG\Model\PhpFunction;
use CG\Model\AbstractPhpStruct;
use CG\Model\PhpClass;
use CG\Model\PhpInterface;
use CG\Model\PhpTrait;

/**
 * The visitor interface required by the DefaultNavigator.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface GeneratorVisitorInterface
{
    /**
     * Resets the visitors internal state to allow re-using the same instance.
     *
     * @return void
     */
    public function reset();

    /**
     * @return void
     */
    public function startVisitingClass(PhpClass $class);
    
    /**
     * @return void
     */
    public function startVisitingInterface(PhpInterface $interface);
    
    /**
     * @return void
     */
    public function startVisitingTrait(PhpTrait $trait);

    /**
     * @return void
     */
    public function startVisitingStructConstants();

    /**
     * @param  PhpConstant $constant
     * @return void
     */
    public function visitStructConstant(PhpConstant $constant);

    /**
     * @return void
     */
    public function endVisitingStructConstants();

    /**
     * @return void
     */
    public function startVisitingProperties();

    /**
     * @return void
     */
    public function visitProperty(PhpProperty $property);

    /**
     * @return void
     */
    public function endVisitingProperties();

    /**
     * @return void
     */
    public function startVisitingMethods();

    /**
     * @return void
     */
    public function visitMethod(PhpMethod $method);

    /**
     * @return void
     */
    public function endVisitingMethods();

    /**
     * @return void
     */
    public function endVisitingClass(PhpClass $class);
    
    /**
     * @return void
     */
    public function endVisitingInterface(PhpInterface $interface);
    
    /**
     * @return void
     */
    public function endVisitingTrait(PhpTrait $trait);

    /**
     * @return void
     */
    public function visitFunction(PhpFunction $function);
}
