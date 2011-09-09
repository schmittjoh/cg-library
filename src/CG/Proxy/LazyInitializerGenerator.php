<?php

namespace CG\Proxy;

use CG\Core\ReflectionUtils;

use CG\Generator\GeneratorUtils;

use CG\Generator\PhpParameter;
use CG\Generator\PhpMethod;
use CG\Generator\PhpProperty;
use CG\Generator\PhpClass;

class LazyInitializerGenerator implements GeneratorInterface
{
    private $prefix = '__CG__';
    private $markerInterface;

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Sets the marker interface which should be implemented by the
     * generated classes.
     *
     * @param string $interface The FQCN of the interface
     */
    public function setMarkerInterface($interface)
    {
        $this->markerInterface = $interface;
    }

    /**
     * Generates the necessary methods in the class.
     *
     * @param \ReflectionClass $originalClass
     * @param PhpClass $class
     * @return void
     */
    public function generate(\ReflectionClass $originalClass, PhpClass $class)
    {
        $methods = ReflectionUtils::getOverrideableMethods($originalClass, true);

        // no public, non final methods
        if (empty($methods)) {
            return;
        }

        if (null !== $this->markerInterface) {
            $class->setImplementedInterfaces(array_merge(
                $class->getImplementedInterfaces(),
                array($this->markerInterface)
            ));
        }

        $initializer = new PhpProperty();
        $initializer->setName($this->prefix.'lazyInitializer');
        $initializer->setVisibility(PhpProperty::VISIBILITY_PRIVATE);
        $class->setProperty($initializer);

        $initialized = new PhpProperty();
        $initialized->setName($this->prefix.'initialized');
        $initialized->setDefaultValue(false);
        $initialized->setVisibility(PhpProperty::VISIBILITY_PRIVATE);
        $class->setProperty($initialized);

        $initializerSetter = new PhpMethod();
        $initializerSetter->setName($this->prefix.'setLazyInitializer');
        $initializerSetter->setBody('$this->'.$this->prefix.'lazyInitializer = $initializer;');

        $parameter = new PhpParameter();
        $parameter->setName('initializer');
        $parameter->setType('\CG\Proxy\LazyInitializerInterface');
        $initializerSetter->setParameter($parameter);
        $class->setMethod($initializerSetter);

        $this->addMethods($class, $methods);

        $initializingMethod = new PhpMethod();
        $initializingMethod->setName($this->prefix.'initialize');
        $initializingMethod->setVisibility(PhpMethod::VISIBILITY_PRIVATE);
        $initializingMethod->setBody(
            'if (null === $this->'.$this->prefix.'lazyInitializer) {'."\n"
            .'    throw new \RuntimeException("'.$this->prefix.'setLazyInitializer() must be called prior to any other public method on this object.");'."\n"
            .'}'."\n\n"
            .'$this->'.$this->prefix.'lazyInitializer->initializeObject($this);'."\n"
            .'$this->'.$this->prefix.'initialized = true;'
        );
        $class->setMethod($initializingMethod);
    }

    private function addMethods(PhpClass $class, array $methods)
    {
        foreach ($methods as $method) {
            $initializingCode = 'if (false === $this->'.$this->prefix.'initialized) {'."\n"
            .'    $this->'.$this->prefix.'initialize();'."\n"
            .'}';

            if ($class->hasMethod($method->name)) {
                $genMethod = $class->getMethod($method->name);
                $genMethod->setBody(
                $initializingCode."\n"
                .$genMethod->getBody()
                );

                continue;
            }

            $genMethod = PhpMethod::fromReflection($method);
            $genMethod->setBody(
            $initializingCode."\n\n"
            .'return '.GeneratorUtils::callMethod($method).';'
            );
            $class->setMethod($genMethod);
        }
    }
}