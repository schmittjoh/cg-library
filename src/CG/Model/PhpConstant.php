<?php

namespace CG\Model;

use CG\Model\Parts\NameTrait;
use CG\Model\Parts\LongDescriptionTrait;
use CG\Model\Parts\DocblockTrait;
use CG\Model\Parts\TypeTrait;
use phpDocumentor\Reflection\DocBlock\Tag\VarTag;
use CG\Utils\ReflectionUtils;

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
    
    	$docblock = new Docblock(ReflectionUtils::getUnindentedDocComment($ref->getDocComment()));
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
		if (!$docblock instanceof Docblock) {
			$docblock = new Docblock();
		}
		$docblock->setText(sprintf("%s\n\n%s", $this->getDescription(), $this->getLongDescription()));
    	
		$var = new VarTag('var', sprintf('%s %s', $this->getType(), $this->getTypeDescription()));
		$docblock->appendTag($var);
    	
    	$this->setDocblock($docblock);
    
    	return $docblock;
    }
}