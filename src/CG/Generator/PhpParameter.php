<?php

namespace CG\Generator;

class PhpParameter
{
    private $name;
    private $defaultValue;
    private $hasDefaultValue = false;
    private $passedByReference = false;
    private $type;

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
        } else if ($class = $ref->getClass()) {
            $parameter->setType($class->name);
        }

        return $parameter;
    }

    public function __construct($name = null)
    {
        $this->name = $name;
    }

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

    public function setPassedByReference($bool)
    {
        $this->passedByReference = (Boolean) $bool;

        return $this;
    }

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
}