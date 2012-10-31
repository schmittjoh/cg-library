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
class PhpParameter
{
    private $name;
    private $defaultValue;
    private $hasDefaultValue = false;
    private $passedByReference = false;
    private $type;
    private $attributes = array();

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
        }

        return $parameter;
    }

    public function __construct($name = null)
    {
        $this->name = $name;
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

    public function isPassedByReference()
    {
        return $this->passedByReference;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    public function removeAttribute($key)
    {
        unset($this->attributes[$key]);
    }

    public function getAttribute($key)
    {
        if ( ! isset($this->attributes[$key])) {
            throw new \InvalidArgumentException(sprintf('There is no attribute named "%s".', $key));
        }

        return $this->attributes[$key];
    }

    public function getAttributeOrElse($key, $default)
    {
        if ( ! isset($this->attributes[$key])) {
            return $default;
        }

        return $this->attributes[$key];
    }

    public function hasAttribute($key)
    {
        return isset($this->attributes[$key]);
    }

    public function setAttributes(array $attrs)
    {
        $this->attributes = $attrs;

        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}
