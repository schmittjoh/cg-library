<?php

namespace CG\Proxy;

interface MethodProxy
{
    function getName();
    function invokeParent(array $args);
}