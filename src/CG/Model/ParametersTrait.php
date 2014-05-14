<?php

namespace CG\Model;

trait ParametersTrait {
	
	/**
	 * @var PhpParameter[]
	 */
	private $parameters = [];
	
	public function setParameters(array $parameters)
	{
		$this->parameters = array_values($parameters);
	
		return $this;
	}
	
	public function addParameter(PhpParameter $parameter)
	{
		$this->parameters[] = $parameter;
	
		return $this;
	}
	
	/**
	 * @param string|integer $nameOrIndex
	 *
	 * @return PhpParameter
	 */
	public function getParameter($nameOrIndex)
	{
		if (is_int($nameOrIndex)) {
			if ( ! isset($this->parameters[$nameOrIndex])) {
				throw new \InvalidArgumentException(sprintf('There is no parameter at position %d (0-based).', $nameOrIndex));
			}
	
			return $this->parameters[$nameOrIndex];
		}
	
		foreach ($this->parameters as $param) {
			assert($param instanceof PhpParameter);
	
			if ($param->getName() === $nameOrIndex) {
				return $param;
			}
		}
	
		throw new \InvalidArgumentException(sprintf('There is no parameter named "%s".', $nameOrIndex));
	}
	
	public function replaceParameter($position, PhpParameter $parameter)
	{
		if ($position < 0 || $position > count($this->parameters)) {
			throw new \InvalidArgumentException(sprintf('The position must be in the range [0, %d].', strlen($this->parameters)));
		}
		$this->parameters[$position] = $parameter;
	
		return $this;
	}
	
	/**
	 * @param integer $position
	 */
	public function removeParameter($position)
	{
		if (!isset($this->parameters[$position])) {
			throw new \InvalidArgumentException(sprintf('There is no parameter at position "%d" does not exist.', $position));
		}
		unset($this->parameters[$position]);
		$this->parameters = array_values($this->parameters);
	
		return $this;
	}
	

	public function getParameters()
	{
		return $this->parameters;
	}
}