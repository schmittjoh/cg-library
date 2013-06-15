<?php

namespace CG\Tests\Generator\Fixture;

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
     */
    final public function __construct($a, array &$b, \stdClass $c, $d = 'foo')
    {
        $this->id = $a;
        if ($d === 'foo'){
            $this->enabled = true;
        }
    }

    abstract protected function foo();

    private static function bar()
    {
        return 'abc';
    }

    private function BraceAbove(){
        $ret = $this->bar();
        return $ret;
    }

    private function BadFormat()

         {
             $ret = $this->bar();
             return $ret;
        }
    private function oneLine(){  return 'abc';  }
}
