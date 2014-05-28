<?php

namespace CG\Model;

use CG\Model\Parts\NameTrait;
use CG\Model\Parts\LongDescriptionTrait;
use CG\Model\Parts\DocblockTrait;
use CG\Model\Parts\TypeTrait;
use CG\Utils\ReflectionUtils;
use gossi\docblock\DocBlock;
use gossi\docblock\tags\VarTag;

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
    
    public static function fromReflection(\Reflection $ref)
    {
    	$constant = new static($ref->name);
    	$constant
	    	->setStatic($ref->isStatic())
	    	->setVisibility($ref->isPublic() ? self::VISIBILITY_PUBLIC : ($ref->isProtected() ? self::VISIBILITY_PROTECTED : self::VISIBILITY_PRIVATE))
    	;
    
    	$docblock = new DocBlock($ref);
    	$constant->setDocblock($docblock);
    	$constant->setDescription($docblock->getShortDescription());
    
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
    
    public function generateDocblock() {
    	$docblock = $this->getDocblock();
		if (!$docblock instanceof DocBlock) {
			$docblock = new DocBlock();
		}
		$docblock->setShortDescription($this->getDescription());
		$docblock->setLongDescription($this->getLongDescription());

		$docblock->appendTag(VarTag::create()
			->setType($this->getType())
			->setDescription($this->getTypeDescription()));
    	
    	$this->setDocblock($docblock);
   
    	return $docblock;
    }
}