<?php

namespace CG\Generator;

use CG\Core\ReflectionUtils;

class PhpMethod extends AbstractPhpMember
{
    private $final = false;
    private $abstract = false;
    private $parameters = array();
    private $body = '';

    public static function create($name = null)
    {
        return new static($name);
    }

    public static function fromReflection(\ReflectionMethod $ref)
    {
        $method = new static();
        $method
            ->setFinal($ref->isFinal())
            ->setAbstract($ref->isAbstract())
            ->setStatic($ref->isStatic())
            ->setVisibility($ref->isPublic() ? self::VISIBILITY_PUBLIC : ($ref->isProtected() ? self::VISIBILITY_PROTECTED : self::VISIBILITY_PRIVATE))
            ->setName($ref->name)
        ;

        if ($docComment = $ref->getDocComment()) {
            $method->setDocblock(ReflectionUtils::getUnindentedDocComment($docComment));
        }

        foreach ($ref->getParameters() as $param) {
            $method->addParameter(static::createParameter($param));
        }

        // FIXME: Extract body?

        return $method;
    }

    protected static function createParameter(\ReflectionParameter $parameter)
    {
        return PhpParameter::fromReflection($parameter);
    }

    public function setFinal($bool)
    {
        $this->final = (Boolean) $bool;

        return $this;
    }

    public function setAbstract($bool)
    {
        $this->abstract = $bool;

        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = array_values($parameters);

        return $this;
    }

    public function addParameter(PhpParameter $parameter)
    {
        $this->parameters[] = $parameter;

        return $this;
    }

    public function replaceParameter($position, PhpParameter $parameter)
    {
        if ($position < 0 || $position > strlen($this->parameters)) {
            throw new \InvalidArgumentException(sprintf('The position must be in the range [0, %d].', strlen($this->parameters)));
        }
        $this->parameters[$position] = $parameter;

        return $this;
    }

    public function removeParameter($position)
    {
        if (!isset($this->parameters[$position])) {
            throw new \InvalidArgumentException(sprintf('There is no parameter at position "%d" does not exist.', $position));
        }
        unset($this->parameters[$position]);
        $this->parameters = array_values($this->parameters);

        return $this;
    }

    public function isFinal()
    {
        return $this->final;
    }

    public function isAbstract()
    {
        return $this->abstract;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}