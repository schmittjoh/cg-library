<?php
require_once(dirname(__FILE__).'/EntityPhp7.php');
require_once(dirname(__FILE__).'/SubFixture/Foo.php');
require_once(dirname(__FILE__).'/SubFixture/Bar.php');
require_once(dirname(__FILE__).'/SubFixture/Baz.php');

function dummyReturnArray(): array {
    return array();
}

function dummyReturnBar(): CG\Tests\Generator\Fixture\SubFixture\Bar
{
    return new CG\Tests\Generator\Fixture\SubFixture\Bar();
}

function dummyReturnBaz(): CG\Tests\Generator\Fixture\SubFixture\Baz
{
    return new CG\Tests\Generator\Fixture\SubFixture\Baz();
}

function dummyReturnBool(): bool {
    return true;
}

function dummyReturnDateTime(): DateTime {
    return new DateTime();
}

function dummyReturnDateTimeZone(): DateTimeZone {
    return new DateTimeZone();
}

function dummyReturnEntityPhp7(): CG\Tests\Generator\Fixture\EntityPhp7 {
    return new CG\Tests\Generator\Fixture\EntityPhp7();
}

function dummyReturnFoo(): CG\Tests\Generator\Fixture\SubFixture\Foo
{
    return new CG\Tests\Generator\Fixture\SubFixture\Foo();
}

function dummyReturnInt(): int {
    return 1;
}

function getArrayReflectionType() {
    return (new ReflectionFunction('dummyReturnArray'))->getReturnType();
}

function getBarReflectionType() {
    return (new ReflectionFunction('dummyReturnBar'))->getReturnType();
}

function getBazReflectionType() {
    return (new ReflectionFunction('dummyReturnBaz'))->getReturnType();
}

function getBoolReflectionType() {
    return (new ReflectionFunction('dummyReturnBool'))->getReturnType();
}

function getDateTimeReflectionType() {
    return (new ReflectionFunction('dummyReturnDateTime'))->getReturnType();
}

function getDateTimeZoneReflectionType() {
    return (new ReflectionFunction('dummyReturnDateTimeZone'))->getReturnType();
}

function getEntityPhp7ReflectionType() {
    return (new ReflectionFunction('dummyReturnEntityPhp7'))->getReturnType();
}

function getFooReflectionType() {
    return (new ReflectionFunction('dummyReturnFoo'))->getReturnType();
}

function getIntReflectionType() {
    return (new ReflectionFunction('dummyReturnInt'))->getReturnType();
}