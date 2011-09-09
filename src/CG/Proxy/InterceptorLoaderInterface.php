<?php

namespace CG\Proxy;

/**
 * Interception Loader.
 *
 * Implementations of this interface are responsible for loading the interceptors
 * for a certain method.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface InterceptorLoaderInterface
{
    /**
     * Loads interceptors.
     *
     * @param \ReflectionMethod $method
     * @return array<MethodInterceptorInterface>
     */
    function loadInterceptors(\ReflectionMethod $method);
}