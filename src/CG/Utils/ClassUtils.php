<?php

namespace CG\Utils;

use CG\Proxy\NamingStrategyInterface;

class ClassUtils {

	final private function __construct() {}
	
	/**
	 * @param string $className
	 */
	public static function getUserClass($className)
	{
		if (false === $pos = strrpos($className, '\\'.NamingStrategyInterface::SEPARATOR.'\\')) {
			return $className;
		}
	
		return substr($className, $pos + NamingStrategyInterface::SEPARATOR_LENGTH + 2);
	}
}