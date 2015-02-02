<?php

namespace CG\Tests\Core;

use CG\Utils\ClassUtils;

class ClassUtilsTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUserClassName()
    {
        $this->assertEquals('Foo', ClassUtils::getUserClass('Foo'));
        $this->assertEquals('Bar', ClassUtils::getUserClass('FOO\__CG__\Bar'));
    }
}