<?php

namespace CG\Tests\Generator;

use CG\Generator\PhpParameter;
use CG\Generator\PhpMethod;
use PHPUnit\Framework\TestCase;

class Php71MethodTest extends TestCase
{
    public function testReturnNullableBuiltInType()
    {
        $method = new PhpMethod();

        $this->assertFalse($method->hasBuiltInReturnType());
        $this->assertSame($method, $method->setReturnType('?string'));
        $this->assertTrue($method->hasBuiltInReturnType());
    }


    public function testItCreatesNullableInTypeFromReflection()
    {
        $class = new class
        {
            public function iAmNullable():?string
            {
                return '';
            }
        };
        $reflection = (new \ReflectionClass($class))->getMethod('iAmNullable');

        $method = PhpMethod::fromReflection($reflection);

        $this->assertTrue($method->hasBuiltInReturnType());
        $this->assertSame('?string', $method->getReturnType());
    }

}
