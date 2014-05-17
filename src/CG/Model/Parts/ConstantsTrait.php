<?php

namespace CG\Model\Parts;

use CG\Model\PhpConstant;
trait ConstantsTrait {

	/**
	 * 
	 * @var PhpConstant[]
	 */
	private $constants = [];

	public function setConstants(array $constants)
	{
		$normalizedConstants = array();
		foreach ($constants as $name => $value) {
			if ( ! $value instanceof PhpConstant) {
				$constValue = $value;
				$value = new PhpConstant($name);
				$value->setValue($constValue);
			}
	
			$normalizedConstants[$name] = $value;
		}
	
		$this->constants = $normalizedConstants;
	
		return $this;
	}
	
	/**
	 * @param string|PhpConstant $name
	 * @param string $value
	 */
	public function setConstant($nameOrConstant, $value = null)
	{
		if ($nameOrConstant instanceof PhpConstant) {
			if (null !== $value) {
				throw new \InvalidArgumentException('If a PhpConstant object is passed, $value must be null.');
			}
	
			$name = $nameOrConstant->getName();
			$constant = $nameOrConstant;
		} else {
			$name = $nameOrConstant;
			$constant = new PhpConstant($nameOrConstant);
			$constant->setValue($value);
		}

		$this->constants[$name] = $constant;
	
		return $this;
	}
	
	/**
	 * @param string $name
	 *
	 * @return boolean
	 */
	public function hasConstant($name)
	{
		return array_key_exists($name, $this->constants);
	}
	
	/**
	 * Returns a constant.
	 *
	 * @param string $name
	 *
	 * @return PhpConstant
	 */
	public function getConstant($name)
	{
		if ( ! isset($this->constants[$name])) {
			throw new \InvalidArgumentException(sprintf('The constant "%s" does not exist.'));
		}
	
		return $this->constants[$name];
	}
	
	/**
	 * @param string $name
	 */
	public function removeConstant($name)
	{
		if (!array_key_exists($name, $this->constants)) {
			throw new \InvalidArgumentException(sprintf('The constant "%s" does not exist.', $name));
		}
	
		unset($this->constants[$name]);
	
		return $this;
	}
	

	public function getConstants($asObjects = false)
	{
		if ($asObjects) {
			return $this->constants;
		}
	
		return array_map(function(PhpConstant $constant) {
			return $constant->getValue();
		}, $this->constants);
	}
}