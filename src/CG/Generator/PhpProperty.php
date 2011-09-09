<?php

namespace CG\Generator;

use CG\Core\ReflectionUtils;

class PhpProperty extends AbstractPhpMember
{
    private $hasDefaultValue = false;
    private $defaultValue;

    public static function create($name = null)
    {
        return new static($name);
    }

    public static function fromReflection(\ReflectionProperty $ref)
    {
        $property = new static();
        $property
            ->setName($ref->name)
            ->setStatic($ref->isStatic())
            ->setVisibility($ref->isPublic() ? self::VISIBILITY_PUBLIC : ($ref->isProtected() ? self::VISIBILITY_PROTECTED : self::VISIBILITY_PRIVATE))
        ;

        if ($docComment = $ref->getDocComment()) {
            $property->setDocblock(ReflectionUtils::getUnindentedDocComment($docComment));
        }

        $defaultProperties = $ref->getDeclaringClass()->getDefaultProperties();
        if (isset($defaultProperties[$ref->name])) {
            $property->setDefaultValue($defaultProperties[$ref->name]);
        }

        return $property;
    }

    public function setDefaultValue($value)
    {
        $this->defaultValue = $value;
        $this->hasDefaultValue = true;

        return $this;
    }

    public function unsetDefaultValue()
    {
        $this->hasDefaultValue = false;
        $this->defaultValue = null;

        return $this;
    }

    public function hasDefaultValue()
    {
        return $this->hasDefaultValue;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }
}