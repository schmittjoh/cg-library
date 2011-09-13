<?php

namespace CG\Core;

abstract class ReflectionUtils
{
    public static function getOverrideableMethods(\ReflectionClass $class, $publicOnly = false)
    {
        $filter = \ReflectionMethod::IS_PUBLIC;

        if (!$publicOnly) {
            $filter |= \ReflectionMethod::IS_PROTECTED;
        }

        return array_filter(
            $class->getMethods($filter),
            function($method) { return !$method->isFinal() && !$method->isStatic(); }
        );
    }

    public static function getUnindentedDocComment($docComment)
    {
        $lines = explode("\n", $docComment);
        for ($i=0,$c=count($lines); $i<$c; $i++) {
            if (0 === $i) {
                $docBlock = $lines[0]."\n";
                continue;
            }

            $docBlock .= ' '.ltrim($lines[$i]);

            if ($i+1 < $c) {
                $docBlock .= "\n";
            }
        }

        return $docBlock;
    }

    private final function __construct() { }
}