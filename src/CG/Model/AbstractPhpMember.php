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

/**
 * Abstract PHP member class.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
abstract class AbstractPhpMember extends AbstractModel implements DocblockInterface
{
	use DocblockTrait;
	use NameTrait;
	use LongDescriptionTrait;
	
    const VISIBILITY_PRIVATE = 'private';
    const VISIBILITY_PROTECTED = 'protected';
    const VISIBILITY_PUBLIC = 'public';

    private $static = false;
    private $visibility = self::VISIBILITY_PUBLIC;
    private $parent;
    
    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     * @param string $visibility
     */
    public function setVisibility($visibility)
    {
        if ($visibility !== self::VISIBILITY_PRIVATE
            && $visibility !== self::VISIBILITY_PROTECTED
            && $visibility !== self::VISIBILITY_PUBLIC) {
            throw new \InvalidArgumentException(sprintf('The visibility "%s" does not exist.', $visibility));
        }

        $this->visibility = $visibility;

        return $this;
    }

    /**
     * @param boolean $bool
     */
    public function setStatic($bool)
    {
        $this->static = (Boolean) $bool;

        return $this;
    }

    public function isStatic()
    {
        return $this->static;
    }

    public function getVisibility()
    {
        return $this->visibility;
    }
    
    /**
     * @param AbstractPhpStruct|null $parent
     * @return $this
     */
    public function setParent($parent) {
    	$this->parent = $parent;
    	return $this;
    }
    
    /**
     * @return AbstractPhpStruct
     */
    public function getParent() {
    	return $this->parent;
    }

}
