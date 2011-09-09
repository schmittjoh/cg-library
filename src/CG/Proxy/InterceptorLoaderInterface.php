<?php

namespace CG\Proxy;

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