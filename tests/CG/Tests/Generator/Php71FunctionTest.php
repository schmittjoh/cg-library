<?php

namespace CG\Tests\Generator;

use CG\Generator\PhpFunction;
use PHPUnit\Framework\TestCase;

class Php71FunctionTest extends TestCase
{
    public function testReturnNullableBuiltInType()
    {
        $method = new PhpFunction();

        $this->assertFalse($method->hasBuiltInReturnType());
        $this->assertSame($method, $method->setReturnType('?string'));
        $this->assertTrue($method->hasBuiltInReturnType());
    }


    public function testItCreatesNullableInTypeFromReflection()
    {
        $f = function ():?string {
            return '';
        };
        $reflection = new \ReflectionFunction($f);

        $method = PhpFunction::fromReflection($reflection);

        $this->assertTrue($method->hasBuiltInReturnType());
        $this->assertSame('?string', $method->getReturnType());
    }

}
