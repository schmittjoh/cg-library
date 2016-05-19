<?php
namespace CG\Tests\Generator\Fixture;
/**
 * @return string
 */
function TestFunction($a){
    if ($a==='abc'){
        return 'abd';
    }else{
        return 'abf';
    }
}