<?php

namespace CG\Proxy;

use CG\Core\AbstractCodeGenerator;

abstract class AbstractProxyClassGenerator extends AbstractCodeGenerator {

	private $namingStrategy;

	/**
	 * @return string
	 */
	public function getClassName(\ReflectionClass $class)
	{
		if (null === $this->namingStrategy) {
			$this->namingStrategy = new DefaultNamingStrategy();
		}
	
		return $this->namingStrategy->getClassName($class);
	}
	
	public function setNamingStrategy(NamingStrategyInterface $namingStrategy = null)
	{
		$this->namingStrategy = $namingStrategy;
	}
}