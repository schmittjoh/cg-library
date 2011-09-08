<?php

namespace CG\Proxy;

use CG\Generator\PhpParameter;
use CG\Generator\PhpMethod;
use CG\Generator\PhpProperty;
use CG\Generator\PhpClass;

class LazyInitializerGenerator
{
    private $prefix;

    public function __construct($prefix = '__CG__')
    {
        $this->prefix = $prefix;
    }

    public function generate(PhpClass $class)
    {
        $initializer = new PhpProperty();
        $initializer->setName($this->prefix.'lazyInitializer');
        $initializer->setVisibility(PhpProperty::VISIBILITY_PRIVATE);
        $class->setProperty($initializer);

        $initializerSetter = new PhpMethod();
        $initializerSetter->setName($this->prefix.'setLazyInitializer');
        $initializerSetter->setBody('$this->'.$this->prefix.'lazyInitializer = $initializer;');

        $parameter = new PhpParameter();
        $parameter->setName('initializer');
        $parameter->setType('\CG\Proxy\LazyInitializerInterface');
        $initializerSetter->setParameter($parameter);
        $class->setMethod($initializerSetter);


    }
}