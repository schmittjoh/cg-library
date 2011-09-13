<?php

namespace CG\Core;

use CG\Generator\DefaultVisitorInterface;
use CG\Generator\PhpClass;
use CG\Generator\DefaultVisitor;
use CG\Generator\DefaultNavigator;

/**
 * The default generator strategy.
 *
 * This strategy allows to change the order in which methods, properties and
 * constants are sorted.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class DefaultGeneratorStrategy implements GeneratorStrategyInterface
{
    private $navigator;
    private $visitor;

    public function __construct(DefaultVisitorInterface $visitor = null)
    {
        $this->navigator = new DefaultNavigator();
        $this->visitor = $visitor ?: new DefaultVisitor();
    }

    public function setConstantSortFunc(\Closure $func = null)
    {
        $this->navigator->setConstantSortFunc($func);
    }

    public function setMethodSortFunc(\Closure $func = null)
    {
        $this->navigator->setMethodSortFunc($func);
    }

    public function setPropertySortFunc(\Closure $func = null)
    {
        $this->navigator->setPropertySortFunc($func);
    }

    public function generate(PhpClass $class)
    {
        $this->visitor->reset();
        $this->navigator->accept($this->visitor, $class);

        return $this->visitor->getContent();
    }
}