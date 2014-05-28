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

use Doctrine\Common\Annotations\PhpParser;
use CG\Utils\ReflectionUtils;
use CG\Model\Parts\QualifiedNameTrait;
use CG\Model\Parts\DocblockTrait;
use CG\Model\Parts\LongDescriptionTrait;
use gossi\docblock\DocBlock;

/**
 * Represents an abstract php struct.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
abstract class AbstractPhpStruct extends AbstractModel implements NamespaceInterface, DocblockInterface
{
	use QualifiedNameTrait;
	use DocblockTrait;
	use LongDescriptionTrait;

    protected static $phpParser;

    private $useStatements = [];
    private $requiredFiles = [];
    
    /**
     * @var PhpMethod[]
     */
    private $methods = [];

    public static function create($name = null)
    {
        return new static($name);
    }

    /**
     * @return PhpMethod
     */
    protected static function createMethod(\ReflectionMethod $method)
    {
        return PhpMethod::fromReflection($method);
    }

    /**
     * @return PhpProperty
     */
    protected static function createProperty(\ReflectionProperty $property)
    {
        return PhpProperty::fromReflection($property);
    }

    public function __construct($name = null)
    {
        $this->setQualifiedName($name);
    }

    public function setRequiredFiles(array $files)
    {
        $this->requiredFiles = $files;

        return $this;
    }

    /**
     * @param string $file
     */
    public function addRequiredFile($file)
    {
        $this->requiredFiles[] = $file;

        return $this;
    }

    public function setUseStatements(array $useStatements)
    {
        $this->useStatements = $useStatements;

        return $this;
    }

    /**
     * @param string $qualifiedName
     * @param string $alias
     */
    public function addUseStatement($qualifiedName, $alias = null)
    {
        if (null === $alias) {
            $alias = substr($qualifiedName, strrpos($qualifiedName, '\\') + 1);
        }

        $this->useStatements[$alias] = $qualifiedName;

        return $this;
    }

	/**
	 * Returns whether the given use statement is present
	 * 
	 * @param string $qualifiedName
	 * @return boolean
	 */
	public function hasUseStatement($qualifiedName) {
		return in_array($qualifiedName, $this->useStatements);
	}

    public function removeUseStatement($qualifiedName) {
    	$offset = array_search($qualifiedName, $this->useStatements);
    	if ($offset !== null) {
    		unset($this->useStatements[$offset]);
    	}
    }

    /**
     * 
     * @param PhpMethod[] $methods
     * @return $this
     */
    public function setMethods(array $methods)
    {
    	foreach ($this->methods as $method) {
    		$method->setParent(null);
    	}
    	
    	$this->methods = [];
    	foreach ($methods as $method) {
    		$this->setMethod($method);
    	}

        return $this;
    }

    public function setMethod(PhpMethod $method)
    {
    	$method->setParent($this);
        $this->methods[$method->getName()] = $method;

        return $this;
    }

    public function getMethod($method)
    {
        if ( ! isset($this->methods[$method])) {
            throw new \InvalidArgumentException(sprintf('The method "%s" does not exist.', $method));
        }

        return $this->methods[$method];
    }

    /**
     * @param string|PhpMethod $method
     */
    public function hasMethod($method)
    {
        if ($method instanceof PhpMethod) {
            $method = $method->getName();
        }

        return isset($this->methods[$method]);
    }

    /**
     * @param string|PhpMethod $method
     */
    public function removeMethod($method)
    {
        if ($method instanceof PhpMethod) {
            $method = $method->getName();
        }

        if (!array_key_exists($method, $this->methods)) {
            throw new \InvalidArgumentException(sprintf('The method "%s" does not exist.', $method));
        }
        $m = $this->methods[$method];
        $m->setParent(null);
        unset($this->methods[$method]);

        return $this;
    }

    public function getRequiredFiles()
    {
        return $this->requiredFiles;
    }

    public function getUseStatements()
    {
        return $this->useStatements;
    }

    public function getMethods()
    {
        return $this->methods;
    }

	public function generateDocblock() {
		$docblock = $this->getDocblock();
		if (!$docblock instanceof DocBlock) {
			$docblock = new DocBlock();
		}
		$docblock->setShortDescription($this->getDescription());
		$docblock->setLongDescription($this->getLongDescription());
		
		foreach ($this->methods as $method) {
			$method->generateDocblock();
		}
		
		return $docblock;
	}

}
