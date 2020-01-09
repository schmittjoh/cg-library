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
 * Represents a PHP parameter.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class PhpParameter extends AbstractBuilder
{
    private $name;
    private $defaultValue;
    private $hasDefaultValue = false;
    private $nullable = false;
    private $passedByReference = false;
    private $type;
    private $typeBuiltin;

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

        if (method_exists($ref, 'getType')) {
            if ($type = $ref->getType()) {
                $parameter->setType((string)$type);
                /*
                 * Types of parameters with default value null are considered
                 * as nullable by ReflectionType but we don't want code generator
                 * to add "?" to the type because in PHP 7.0 this would fail.
                 *
                 * If instead the code generator just sets the default value to null class inheritance compatibility
                 * is maintained even if generated code will have just the default value null and not the "?".
                 */
                if (!(
                    $ref->isDefaultValueAvailable()
                    && is_null($ref->getDefaultValue())
                )) {
                    $parameter->setNullable($type->allowsNull());
                }
            }
        } else {
            if ($ref->isArray()) {
                $parameter->setType('array');
            } elseif ($class = $ref->getClass()) {
                $parameter->setType($class->name);
            } elseif (method_exists($ref, 'isCallable') && $ref->isCallable()) {
                $parameter->setType('callable');
            }
        }

        return $parameter;
    }

    public function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * @param boolean $nullable
     */
    public function setNullable($nullable)
    {
        $this->nullable = $nullable;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setDefaultValue($value)
    {
        $this->defaultValue = $value;
        $this->hasDefaultValue = true;

        return $this;
    }

    public function unsetDefaultValue()
    {
        $this->defaultValue = null;
        $this->hasDefaultValue = false;

        return $this;
    }

    /**
     * @param boolean $bool
     */
    public function setPassedByReference($bool)
    {
        $this->passedByReference = (Boolean) $bool;

        return $this;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
        $this->typeBuiltin = BuiltinType::isBuiltIn($type);

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function hasDefaultValue()
    {
        return $this->hasDefaultValue;
    }

    public function isNullable()
    {
        return $this->nullable;
    }

    public function isPassedByReference()
    {
        return $this->passedByReference;
    }

    public function getType()
    {
        return $this->type;
    }

    public function hasType()
    {
        return null !== $this->type;
    }

    public function hasBuiltinType()
    {
        return $this->typeBuiltin;
    }
}
