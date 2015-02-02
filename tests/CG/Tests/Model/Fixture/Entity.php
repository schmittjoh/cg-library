<?php

namespace CG\Tests\Model\Fixture;

/**
 * Doc Comment.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
abstract class Entity
{
    /**
     * @var integer
     */
    private $id;
    private $enabled = false;

    /**
     * Another doc comment.
     *
     * @param unknown_type $a
     * @param array        $b
     * @param \stdClass    $c
     * @param string       $d
     * @param callable     $e
     */
    final public function __construct($a, array &$b, \stdClass $c, $d = 'foo', callable $e)
    {
    }

    abstract protected function foo();

    private static function bar()
    {
    }
}
