<?php

namespace CG\Proxy;

/**
 * Represents a method invocation.
 *
 * This object contains information for the method invocation, such as the object
 * on which the method is invoked, and the arguments that are passed to the method.
 *
 * Before the actual method is called, first all the interceptors must call the
 * proceed() method on this class.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
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

    /**
     * Proceeds down the call-chain and eventually calls the original method.
     *
     * @return mixed
     */
    public function proceed()
    {
        if (isset($this->interceptors[$this->pointer])) {
            return $this->interceptors[$this->pointer++]->intercept($this);
        }

        return $this->reflection->invokeArgs($this->object, $this->arguments);
    }
}