<?php

namespace CG\Model;

class PhpConstant extends AbstractModel implements GenerateableInterface, DocblockInterface
{
    use NameTrait;
    use LongDescriptionTrait;
   	use DocblockTrait;
    
    private $value;

    public function __construct($name = null)
    {
        $this->setName($name);
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
    	$docblock = new Docblock();
    	$docblock
	    	->setDescription($this->description)
	    	->setLongDescription($this->longDescription)
	    	->setVar($this->type, $this->typeDescription)
    	;
    	
    	$this->setDocblock($docblock);
    
    	return $docblock;
    }
}