<?php

namespace CG\Proxy;

final class MethodInvocation
{
    public $reflection;
    public $object;
    public $arguments;

    private $interceptors;
    private $pointer;

    public function __construct(\ReflectionMethod $reflection, $object, array $arguments, array $interceptors)
    {
        $this->reflection = $reflection;
        $this->object = $object;
        $this->arguments = $arguments;
        $this->interceptors = $interceptors;
        $this->pointer = 0;
    }

    public function proceed()
    {
        if (isset($this->interceptors[$this->pointer])) {
            return $this->interceptors[$this->pointer++]->intercept($this);
        }

        return $this->reflection->invokeArgs($this->object, $this->arguments);
    }
}