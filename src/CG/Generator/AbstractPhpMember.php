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
}