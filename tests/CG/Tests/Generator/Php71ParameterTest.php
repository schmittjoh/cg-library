<?php

namespace CG\Tests\Generator;

use CG\Generator\PhpFunction;
use CG\Generator\PhpParameter;
use CG\Generator\PhpMethod;
use PHPUnit\Framework\TestCase;

class Php71ParameterTest extends TestCase
{
    public function testReturnNullableBuiltInType()
    {
        $method = new PhpParameter();

        $this->assertFalse($method->hasBuiltinType());
        $this->assertSame($method, $method->setType('?string'));
        $this->assertTrue($method->hasBuiltinType());
    }


    public function testItCreatesNullableInTypeFromReflection()
    {
        $f = function (?string $param) {
        };
        $reflection = (new \ReflectionFunction($f))->getParameters()[0];

        $method = PhpParameter::fromReflection($reflection);

        $this->assertTrue($method->hasBuiltinType());
        $this->assertSame('?string', $method->getType());
    }

}
