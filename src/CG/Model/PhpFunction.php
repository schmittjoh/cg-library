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
use CG\Model\Parts\QualifiedNameTrait;
use CG\Model\Parts\DocblockTrait;
use CG\Model\Parts\ParametersTrait;
use CG\Model\Parts\BodyTrait;
use CG\Model\Parts\ReferenceReturnTrait;
use CG\Model\Parts\TypeTrait;
use CG\Model\Parts\LongDescriptionTrait;
use gossi\docblock\DocBlock;
use gossi\docblock\tags\ReturnTag;

/**
 * Represents a PHP function.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class PhpFunction extends AbstractModel implements GenerateableInterface, NamespaceInterface, DocblockInterface
{
    use QualifiedNameTrait;
    use DocblockTrait;
    use ParametersTrait;
    use BodyTrait;
    use ReferenceReturnTrait;
    use TypeTrait;
    use LongDescriptionTrait;

    public static function fromReflection(\ReflectionFunction $ref)
    {
        $function = PhpFunction::create($ref->name)
        	->setReferenceReturned($ref->returnsReference())
        	->setBody(ReflectionUtils::getFunctionBody($ref))
        ;
        
        $docblock = new DocBlock($ref);
        $function->setDocblock($docblock);
        $function->setDescription($docblock->getShortDescription());
        $function->setLongDescription($docblock->getLongDescription());

        foreach ($ref->getParameters() as $refParam) {
            assert($refParam instanceof \ReflectionParameter); // hmm - assert here?

            $param = PhpParameter::fromReflection($refParam);
            $function->addParameter($param);
        }

        return $function;
    }

    public static function create($name = null)
    {
        return new static($name);
    }

    public function __construct($name = null)
    {
        $this->setQualifiedName($name);
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
