<?php
namespace CG\Tests\Generator;

if (PHP_VERSION_ID >= 70300) {
    require_once(dirname(__FILE__).'/Fixture/DummyReflectionTypes.php');
}

use CG\Generator\PhpProperty;
use CG\Generator\PhpParameter;
use CG\Generator\PhpMethod;
use CG\Generator\PhpClass;

class Php73ClassTest extends \PHPUnit_Framework_TestCase
{
    public function testFromReflection()
    {
        if (PHP_VERSION_ID < 70300) {
           $this->markTestSkipped("Test is only valid for PHP >=7.3");
        }
        $class = new PhpClass();
        $class
            ->setName('CG\Tests\Generator\Fixture\EntityPhp73')
            ->setDocblock('/**
 * Doc Comment.
 *
 * @author Igor Blanco <iblanco@binovo.es>
 */'
             );

        $class->setMethod(PhpMethod::create()
            ->setName('doNothing')
            ->setVisibility('public')
            ->setReturnType(getVoidReflectionType())
        );

        $class->setMethod(PhpMethod::create()
            ->setName('doNothing2')
            ->setVisibility('public')
            ->setReturnType(getNullableStringReflectionType())
        );

        $this->assertEquals($class, PhpClass::fromReflection(new \ReflectionClass('CG\Tests\Generator\Fixture\EntityPhp73')));
    }
}
