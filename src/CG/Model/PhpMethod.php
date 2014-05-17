<?php

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace CG\Model;

use CG\Utils\ReflectionUtils;
use CG\Model\Parts\AbstractTrait;
use CG\Model\Parts\FinalTrait;
use CG\Model\Parts\ParametersTrait;
use CG\Model\Parts\BodyTrait;

/**
 * Represents a PHP method.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class PhpMethod extends AbstractPhpMember
{
	use AbstractTrait;
    use FinalTrait;
    use ParametersTrait;
    use BodyTrait;
	
    private $referenceReturned = false;

    /**
     * @param string|null $name
     */
    public static function create($name = null)
    {
        return new static($name);
    }

    public static function fromReflection(\ReflectionMethod $ref)
    {
        $method = new static($ref->name);
        $method
            ->setFinal($ref->isFinal())
            ->setAbstract($ref->isAbstract())
            ->setStatic($ref->isStatic())
            ->setVisibility($ref->isPublic() ? self::VISIBILITY_PUBLIC : ($ref->isProtected() ? self::VISIBILITY_PROTECTED : self::VISIBILITY_PRIVATE))
            ->setReferenceReturned($ref->returnsReference())
        ;

        if ($docComment = $ref->getDocComment()) {
            $method->setDocblock(ReflectionUtils::getUnindentedDocComment($docComment));
        }

        foreach ($ref->getParameters() as $param) {
            $method->addParameter(static::createParameter($param));
        }

        // FIXME: Extract body?
        return $method;
    }

    /**
     * @return PhpParameter
     */
    protected static function createParameter(\ReflectionParameter $parameter)
    {
        return PhpParameter::fromReflection($parameter);
    }

    /**
     * @param boolean $bool
     */
    public function setReferenceReturned($bool)
    {
        $this->referenceReturned = (Boolean) $bool;

        return $this;
    }    

    public function isReferenceReturned()
    {
        return $this->referenceReturned;
    }

    
	/* (non-PHPdoc)
	 * @see \CG\Model\AbstractModel::generateDocblock()
	 */
	public function generateDocblock() {
		$docblock = new Docblock();
		$docblock
			->setDescription($this->description)
			->setLongDescription($this->longDescription)
			->setReturn($this->type, $this->typeDescription)
		;
		
		foreach ($this->parameters as $param) {
			$docblock->addParam($param->getName(), $param->getType(), $param->getDescription());
		}
		
		$this->setDocblock($docblock);
		
		return $docblock;
	}

}
