<?php

namespace CG\Generator;

/**
 * The default navigator.
 *
 * This class is responsible for the default traversal algorithm of the different
 * code elements.
 *
 * Unlike other visitor pattern implementations, this allows to separate the
 * traversal logic from the objects that are traversed.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class DefaultNavigator
{
    private $constantSortFunc;
    private $propertySortFunc;
    private $methodSortFunc;

    /**
     * Sets a custom constant sorting function.
     *
     * @param \Closure $func
     */
    public function setConstantSortFunc(\Closure $func = null)
    {
        $this->constantSortFunc = $func;
    }

    /**
     * Sets a custom property sorting function.
     *
     * @param \Closure $func
     */
    public function setPropertySortFunc(\Closure $func = null)
    {
        $this->propertySortFunc = $func;
    }

    /**
     * Sets a custom method sorting function.
     *
     * @param \Closure $func
     */
    public function setMethodSortFunc(\Closure $func = null)
    {
        $this->methodSortFunc = $func;
    }

    public function accept(DefaultVisitorInterface $visitor, PhpClass $class)
    {
        $visitor->startVisitingClass($class);

        $constants = $class->getConstants();
        if (!empty($constants)) {
            uksort($constants, $this->getConstantSortFunc());

            $visitor->startVisitingConstants();
            foreach ($constants as $name => $value) {
                $visitor->visitConstant($name, $value);
            }
            $visitor->endVisitingConstants();
        }

        $properties = $class->getProperties();
        if (!empty($properties)) {
            usort($properties, $this->getPropertySortFunc());

            $visitor->startVisitingProperties();
            foreach ($properties as $property) {
                $visitor->visitProperty($property);
            }
            $visitor->endVisitingProperties();
        }

        $methods = $class->getMethods();
        if (!empty($methods)) {
            usort($methods, $this->getMethodSortFunc());

            $visitor->startVisitingMethods();
            foreach ($methods as $method) {
                $visitor->visitMethod($method);
            }
            $visitor->endVisitingMethods();
        }

        $visitor->endVisitingClass($class);
    }

    private function getConstantSortFunc()
    {
        return $this->constantSortFunc ?: 'strcasecmp';
    }

    private function getMethodSortFunc()
    {
        if (null !== $this->methodSortFunc) {
            return $this->methodSortFunc;
        }

        static $defaultSortFunc;
        if (empty($defaultSortFunc)) {
            $defaultSortFunc = function($a, $b) {
                if ($a->isStatic() !== $isStatic = $b->isStatic()) {
                    return $isStatic ? 1 : -1;
                }

                if (($aV = $a->getVisibility()) !== $bV = $b->getVisibility()) {
                    $aV = 'public' === $aV ? 3 : ('protected' === $aV ? 2 : 1);
                    $bV = 'public' === $bV ? 3 : ('protected' === $bV ? 2 : 1);

                    return $aV > $bV ? -1 : 1;
                }

                $rs = strcasecmp($a->getName(), $b->getName());
                if (0 === $rs) {
                    return 0;
                }

                return $rs > 0 ? -1 : 1;
            };
        }

        return $defaultSortFunc;
    }

    private function getPropertySortFunc()
    {
        if (null !== $this->propertySortFunc) {
            return $this->propertySortFunc;
        }

        static $defaultSortFunc;
        if (empty($defaultSortFunc)) {
            $defaultSortFunc = function($a, $b) {
                if (($aV = $a->getVisibility()) !== $bV = $b->getVisibility()) {
                    $aV = 'public' === $aV ? 3 : ('protected' === $aV ? 2 : 1);
                    $bV = 'public' === $bV ? 3 : ('protected' === $bV ? 2 : 1);

                    return $aV > $bV ? -1 : 1;
                }

                $rs = strcasecmp($a->getName(), $b->getName());
                if (0 === $rs) {
                    return 0;
                }

                return $rs > 0 ? -1 : 1;
            };
        }

        return $defaultSortFunc;
    }
}