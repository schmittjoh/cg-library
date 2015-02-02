<?php

namespace CG\Model;

abstract class AbstractModel
{
    private $attributes = [];
    
    protected $description;

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    public function removeAttribute($key)
    {
    	$val = $this->attributes[$key];
        unset($this->attributes[$key]);
        return $val;
    }

    public function getAttribute($key)
    {
        if ( ! isset($this->attributes[$key])) {
            throw new \InvalidArgumentException(sprintf('There is no attribute named "%s".', $key));
        }

        return $this->attributes[$key];
    }

    /**
     * @param string $key
     */
    public function getAttributeOrElse($key, $default)
    {
        if ( ! isset($this->attributes[$key])) {
            return $default;
        }

        return $this->attributes[$key];
    }

    public function hasAttribute($key)
    {
        return isset($this->attributes[$key]);
    }

    public function setAttributes(array $attrs)
    {
        $this->attributes = $attrs;

        return $this;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
	
	/**
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 *
	 * @param string $description        	
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
}