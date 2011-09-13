<?php

namespace CG\Proxy;

class RegexInterceptionLoader implements InterceptorLoaderInterface
{
    private $interceptors;

    public function __construct(array $interceptors = array())
    {
        $this->interceptors = $interceptors;
    }

    public function loadInterceptors(\ReflectionMethod $method)
    {
        $signature = $method->class.'::'.$method->name;

        $matchingInterceptors = array();
        foreach ($this->interceptors as $pattern => $interceptor) {
            if (preg_match('#'.$pattern.'#', $signature)) {
                $matchingInterceptors[] = $this->initializeInterceptor($interceptor);
            }
        }

        return $matchingInterceptors;
    }

    protected function initializeInterceptor($interceptor)
    {
        return $interceptor;
    }
}