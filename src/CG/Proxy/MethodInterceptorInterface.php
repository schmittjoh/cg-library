<?php

namespace CG\Proxy;

/**
 * Interface for Method Interceptors.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface MethodInterceptorInterface
{
    /**
     * Called when intercepting a method call.
     *
     * @param object $object The object on which the method has been invoked
     * @param \ReflectionMethod $method The original method which has been invoked
     * @param array $args The arguments the method has been invoked with
     */
    function intercept($object, \ReflectionMethod $method, array $args);
}