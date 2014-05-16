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

    private $referenceReturned = false;

    public static function fromReflection(\ReflectionFunction $ref)
    {
        $function = new static();

        if (false === $pos = strrpos($ref->name, '\\')) {
            $function->setName(substr($ref->name, $pos + 1));
            $function->setNamespace(substr($ref->name, $pos));
        } else {
            $function->setName($ref->name);
        }

        $function->referenceReturned = $ref->returnsReference();
        $function->docblock = ReflectionUtils::getUnindentedDocComment($ref->getDocComment());

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
        $this->setName($name);
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
	    	->setVar($this->type, $this->typeDescription)
    	;
    	
    	$this->setDocblock($docblock);
    
    	return $docblock;
    }
}
