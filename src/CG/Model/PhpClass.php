<?php
namespace CG\Model;

use CG\Model\Parts\InterfacesTrait;
use CG\Model\Parts\AbstractTrait;
use CG\Model\Parts\FinalTrait;
use CG\Model\Parts\ConstantsTrait;
use CG\Model\Parts\PropertiesTrait;
use CG\Model\Parts\TraitsTrait;
use Doctrine\Common\Annotations\PhpParser;
use CG\Utils\ReflectionUtils;

class PhpClass extends AbstractPhpStruct implements GenerateableInterface, TraitsInterface {

	use InterfacesTrait;
	use AbstractTrait;
	use FinalTrait;
	use ConstantsTrait;
	use PropertiesTrait;
	use TraitsTrait;
	
	private $parentClassName;
	
	public static function fromReflection(\ReflectionClass $ref)
	{
		$class = new static();
		$class
			->setQualifiedName($ref->name)
			->setAbstract($ref->isAbstract())
			->setFinal($ref->isFinal())
			->setConstants($ref->getConstants())
		;
	
		if (null === self::$phpParser) {
			self::$phpParser = new PhpParser();
		}
	
		$class->setUseStatements(self::$phpParser->parseClass($ref));
	
	
        if ($doc = $ref->getDocComment()) {
	        $docblock = new Docblock(ReflectionUtils::getUnindentedDocComment($doc));
	        $class->setDocblock($docblock);
	        $class->setDescription($docblock->getShortDescription());
	        $class->setLongDescription($docblock->getLongDescription());
        }
	
		foreach ($ref->getMethods() as $method) {
			$class->setMethod(static::createMethod($method));
		}
	
		foreach ($ref->getProperties() as $property) {
			$class->setProperty(static::createProperty($property));
		}

		return $class;
	}
	
	public function __construct($name = null)
	{
		parent::__construct($name);
	}

	public function getParentClassName()
	{
		return $this->parentClassName;
	}
	
	/**
	 * @param string|null $name
	 */
	public function setParentClassName($name)
	{
		$this->parentClassName = $name;
	
		return $this;
	}

	public function generateDocblock() {
		$docblock = parent::generateDocblock();
	
		foreach ($this->constants as $constant) {
			$constant->generateDocblock();
		}
		
		foreach ($this->properties as $prop) {
			$prop->generateDocblock();
		}
		
		$this->setDocblock($docblock);
	
		return $docblock;
	}
}