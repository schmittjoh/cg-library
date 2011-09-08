<?php

namespace CG\Proxy;

class LazyInitializerCallback implements CallbackInterface
{
    public function onMethodCall($object, array $arguments, MethodProxy $proxy)
    {
    }
}