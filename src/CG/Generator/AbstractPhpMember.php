<?php

namespace CG\Generator;

abstract class AbstractPhpMember
{
    const VISIBILITY_PRIVATE = 'private';
    const VISIBILITY_PROTECTED = 'protected';
    const VISIBILITY_PUBLIC = 'public';

    private $static = false;
    private $visibility = self::VISIBILITY_PUBLIC;
    private $name;
    private $docblock;

    public function __construct($name = null)
    {
        $this->setName($name);
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setVisibility($visibility)
    {
        if ($visibility !== self::VISIBILITY_PRIVATE
            && $visibility !== self::VISIBILITY_PROTECTED
            && $visibility !== self::VISIBILITY_PUBLIC) {
            throw new \InvalidArgumentException(sprintf('The visibility "%s" does not exist.', $visibility));
        }

        $this->visibility = $visibility;

        return $this;
    }

    public function setStatic($bool)
    {
        $this->static = (Boolean) $bool;

        return $this;
    }

    public function setDocblock($doc)
    {
        $this->docblock = $doc;

        return $this;
    }

    public function isStatic()
    {
        return $this->static;
    }

    public function getVisibility()
    {
        return $this->visibility;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDocblock()
    {
        return $this->docblock;
    }

    protected function updateFromReflection($ref)
    {
        if (!$ref instanceof \ReflectionProperty && !$ref instanceof \ReflectionMethod) {
            throw new \InvalidArgumentException('$ref must be an instance of \ReflectionProperty, or \ReflectionMethod.');
        }

        $this->static = $ref->isStatic();

        $modifiers = $ref->getModifiers();
        if ($ref instanceof \ReflectionProperty) {
            if (0 !== $modifiers & \ReflectionProperty::IS_PUBLIC) {
                $this->visibility = self::VISIBILITY_PUBLIC;
            } else if (0 !== $modifiers & \ReflectionProperty::IS_PROTECTED) {
                $this->visibility = self::VISIBILITY_PROTECTED;
            } else {
                $this->visibility = self::VISIBILITY_PRIVATE;
            }
        } else {
            if (0 !== $modifiers & \ReflectionMethod::IS_PUBLIC) {
                $this->visibility = self::VISIBILITY_PUBLIC;
            } else if (0 !== $modifiers & \ReflectionMethod::IS_PROTECTED) {
                $this->visibility = self::VISIBILITY_PROTECTED;
            } else {
                $this->visibility = self::VISIBILITY_PRIVATE;
            }
        }

        $this->name = $ref->name;
        $this->docblock = $ref->getDocComment();
    }
}