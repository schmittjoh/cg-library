<?php

namespace CG\Model;

use CG\Model\Parts\NameTrait;
use CG\Model\Parts\LongDescriptionTrait;
use CG\Model\Parts\DocblockTrait;
use CG\Model\Parts\TypeTrait;

class PhpConstant extends AbstractModel implements GenerateableInterface, DocblockInterface
{
    use NameTrait;
    use LongDescriptionTrait;
   	use DocblockTrait;
   	use TypeTrait;
    
    private $value;

    public static function create($name = null, $value = null) {
    	$constant = new static();
    	$constant
    		->setName($name)
    		->setValue($value)
    	;
    	
    	return $constant;
    }
    
    public function __construct($name = null, $value = null)
    {
        $this->setName($name);
        $this->setValue($value);
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }
    
    /* (non-PHPdoc)
     * @see \CG\Model\AbstractModel::generateDocblock()
    */
    public function generateDocblock() {
    	$docblock = $this->getDocblock();
    	if (!$docblock instanceof Docblock) {
    		$docblock = new Docblock();
    	}
    	$docblock
	    	->setDescription($this->getDescription())
	    	->setLongDescription($this->getLongDescription());
    	
    	if ($this->getType() != '') {
	    	$docblock->setVar($this->getType(), $this->getTypeDescription());
    	}
    	
    	$this->setDocblock($docblock);
    
    	return $docblock;
    }
}