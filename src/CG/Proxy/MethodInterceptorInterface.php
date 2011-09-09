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
     * @param MethodInvocation $invocation
     */
    function intercept(MethodInvocation $invocation);
}