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
    public function reset();

    public function startVisitingClass(PhpClass $class);
    public function startVisitingConstants();
    /**
     * @param integer|string $name
     */
    public function visitConstant($name, $value);
    public function endVisitingConstants();
    public function startVisitingProperties();
    public function visitProperty(PhpProperty $property);
    public function endVisitingProperties();
    public function startVisitingMethods();
    public function visitMethod(PhpMethod $method);
    public function endVisitingMethods();
    public function endVisitingClass(PhpClass $class);
    public function visitFunction(PhpFunction $function);
}
