<?php

namespace CG\Proxy;

interface LazyInitializerInterface
{
    function initializeObject($object);
}