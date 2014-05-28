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
use CG\Model\Parts\ReferenceReturnTrait;
use gossi\docblock\tags\ReturnTag;
use gossi\docblock\DocBlock;

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
    use ReferenceReturnTrait;
    
    /**
     * @param string|null $name
     */
    public static function create($name = null) {
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
            ->setBody(ReflectionUtils::getFunctionBody($ref))
        ;

        if ($ref->getDocComment()) {
	        $docblock = new DocBlock($ref);
	        $method->setDocblock($docblock);
	        $method->setDescription($docblock->getShortDescription());
	        $method->setLongDescription($docblock->getLongDescription());
        }

        foreach ($ref->getParameters() as $param) {
            $method->addParameter(static::createParameter($param));
        }

        return $method;
    }

    /**
     * @return PhpParameter
     */
    protected static function createParameter(\ReflectionParameter $parameter) {
        return PhpParameter::fromReflection($parameter);
    }

	/* (non-PHPdoc)
	 * @see \CG\Model\AbstractModel::generateDocblock()
	 */
	public function generateDocblock() {
	    $docblock = $this->getDocblock();
		if (!$docblock instanceof Docblock) {
			$docblock = new DocBlock();
		}
		$docblock->setShortDescription($this->getDescription());
		$docblock->setLongDescription($this->getLongDescription());
		
		if ($this->getType()) {
			$docblock->appendTag(ReturnTag::create()
					->setType($this->getType())
					->setDescription($this->getTypeDescription()));
		}
		
		foreach ($this->parameters as $param) {
			$docblock->appendTag($param->getDocblockTag());
		}
		 
		$this->setDocblock($docblock);
		
		return $docblock;
	}

}
