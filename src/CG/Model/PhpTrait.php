<?php

namespace CG\Model;

use CG\Model\Parts\PropertiesTrait;
use CG\Model\Parts\TraitsTrait;
use CG\Utils\ReflectionUtils;
use Doctrine\Common\Annotations\PhpParser;

class PhpTrait extends AbstractPhpStruct implements GenerateableInterface, TraitsInterface
{
	use PropertiesTrait;
	use TraitsTrait;
	
	public static function fromReflection(\ReflectionClass $ref)
	{
		$trait = new static();
		$trait
			->setQualifiedName($ref->name)
		;
	
		if (null === self::$phpParser) {
			self::$phpParser = new PhpParser();
		}
	
		$trait->setUseStatements(self::$phpParser->parseClass($ref));
	
		$docblock = new Docblock(ReflectionUtils::getUnindentedDocComment($ref->getDocComment()));
		$trait->setDocblock($docblock);
		$trait->setDescription($docblock->getShortDescription());
		$trait->setLongDescription($docblock->getLongDescription());
	
		foreach ($ref->getMethods() as $method) {
			$trait->setMethod(static::createMethod($method));
		}
	
		foreach ($ref->getProperties() as $property) {
			$trait->setProperty(static::createProperty($property));
		}
	
		return $trait;
	}

	public function __construct($name = null)
	{
		parent::__construct($name);
	}
	
	/* (non-PHPdoc)
	 * @see \CG\Model\AbstractModel::generateDocblock()
	*/
	public function generateDocblock() {
		$docblock = parent::generateDocblock();

		foreach ($this->properties as $prop) {
			$prop->generateDocblock();
		}
		
		$this->setDocblock($docblock);
	
		return $docblock;
	}
}