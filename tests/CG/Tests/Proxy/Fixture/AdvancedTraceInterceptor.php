<?php

namespace CG\Tests\Proxy\Fixture;

use CG\Proxy\AdvancedMethodInvocation;
use CG\Proxy\AdvancedMethodInterceptorInterface;

class AdvancedTraceInterceptor implements AdvancedMethodInterceptorInterface
{
    private $log;

    public function getLog()
    {
        return $this->log;
    }

    public function intercept(AdvancedMethodInvocation $method)
    {
        $message = sprintf('%s::%s(', $method->reflectionClass->name, $method->reflection->name);

        $logArgs = array();
        foreach ($method->arguments as $arg) {
            $logArgs[] = var_export($arg, true);
        }
        $this->log[] = $message.implode(', ', $logArgs).')';

        return $method->proceed();
    }
}