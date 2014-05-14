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

namespace CG\Model;

/**
 * Represents a PHP parameter.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class PhpParameter extends AbstractModel
{
    use NameTrait;
    use DefaultValueTrait;
    use TypeTrait;
    
    private $passedByReference = false;

    /**
     * @param string|null $name
     */
    public static function create($name = null)
    {
        return new static($name);
    }

    public static function fromReflection(\ReflectionParameter $ref)
    {
        $parameter = new static();
        $parameter
            ->setName($ref->name)
            ->setPassedByReference($ref->isPassedByReference())
        ;

        if ($ref->isDefaultValueAvailable()) {
            $parameter->setDefaultValue($ref->getDefaultValue());
        }

        if ($ref->isArray()) {
            $parameter->setType('array');
        } elseif ($class = $ref->getClass()) {
            $parameter->setType($class->name);
        } elseif (method_exists($ref, 'isCallable') && $ref->isCallable()) {
            $parameter->setType('callable');
        }

        return $parameter;
    }

    public function __construct($name = null)
    {
        $this->setName($name);
    }

    /**
     * @param boolean $bool
     */
    public function setPassedByReference($bool)
    {
        $this->passedByReference = (Boolean) $bool;

        return $this;
    }

    public function isPassedByReference()
    {
        return $this->passedByReference;
    }

}
