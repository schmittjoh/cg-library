<?php

namespace CG\Core;

interface NamingStrategyInterface
{
    const SEPARATOR = '__CG__';

    function getClassName(\ReflectionClass $class);
}