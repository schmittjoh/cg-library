<?php

namespace CG\Proxy;

/**
 * Interface for Method Interceptors.
 *
 * Implementations of this interface can execute custom code before, and after the
 * invocation of the actual method. In addition, they can also catch, or throw
 * exceptions, modify the return value, or modify the arguments.
 *
 * This is also known as around advice in AOP terminology.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface MethodInterceptorInterface
{
    /**
     * Called when intercepting a method call.
     *
     * @param MethodInvocation $invocation
     * @return mixed the return value for the method invocation
     * @throws \Exception may throw any exception
     */
    function intercept(MethodInvocation $invocation);
}