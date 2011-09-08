<?php

namespace CG\Proxy;

interface CallbackInterface
{
    function onMethodCall($object, array $arguments, MethodProxy $proxy);
}