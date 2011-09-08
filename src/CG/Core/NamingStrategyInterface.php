<?php

namespace CG\Core;

interface NamingStrategyInterface
{
    function getClassName(\ReflectionClass $class);
}